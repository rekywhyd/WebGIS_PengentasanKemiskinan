<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LaporanMasuk;

class FetchEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch unread emails with subject LAPORAN BANTUAN via IMAP';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $server = '{imap.gmail.com:993/imap/ssl}INBOX';
        $username = 'bantuansambas@gmail.com'; 
        $password = 'tkro trmt vxli yekm'; 

        $this->info('[' . date('Y-m-d H:i:s') . '] Memulai proses penarikan email...');

        if (!function_exists('imap_open')) {
            $this->error('Ekstensi IMAP tidak diaktifkan pada instalasi PHP ini.');
            return;
        }

        $inbox = @imap_open($server, $username, $password);

        if (!$inbox) {
            $this->error('Gagal terhubung ke server IMAP: ' . imap_last_error());
            return;
        }

        $this->info("Berhasil terhubung ke email: $username");

        $search_criteria = 'UNSEEN SUBJECT "LAPORAN BANTUAN"';
        $emails = imap_search($inbox, $search_criteria);

        if ($emails) {
            $this->info("Ditemukan " . count($emails) . " email laporan baru.");

            foreach ($emails as $email_number) {
                $overview = imap_fetch_overview($inbox, $email_number, 0);
                
                $subjek = $overview[0]->subject ?? '(Tanpa Subjek)';
                $pengirim = $overview[0]->from ?? '(Tanpa Pengirim)';
                $tanggal_email = isset($overview[0]->date) ? date('Y-m-d H:i:s', strtotime($overview[0]->date)) : now();
                
                $structure = imap_fetchstructure($inbox, $email_number);
                
                $pesan = $this->extractBody($inbox, $email_number, $structure);
                $attachments = $this->extractAttachments($inbox, $email_number, $structure);

                $lampiran_paths = [];
                $upload_dir = storage_path('app/public/uploads');
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                foreach ($attachments as $at) {
                    $file_ext = pathinfo($at['filename'], PATHINFO_EXTENSION);
                    if (empty($file_ext)) {
                        $file_ext = 'bin';
                    }
                    $new_filename = time() . '_' . uniqid() . '.' . $file_ext;
                    $file_path = $upload_dir . '/' . $new_filename;
                    file_put_contents($file_path, $at['data']);
                    $lampiran_paths[] = 'uploads/' . $new_filename;
                }
                $lampiran_str = implode(',', $lampiran_paths);

                $laporan = LaporanMasuk::create([
                    'pengirim' => $pengirim,
                    'tanggal' => $tanggal_email,
                    'subjek' => $subjek,
                    'pesan' => trim($pesan),
                    'lampiran' => $lampiran_str,
                    'status' => 'Menunggu'
                ]);

                if ($laporan) {
                    $this->info("- Berhasil menyimpan laporan dari: $pengirim");
                } else {
                    $this->error("- Gagal menyimpan laporan dari: $pengirim.");
                }
            }
        } else {
            $this->info("Tidak ada email laporan baru.");
        }

        imap_close($inbox);
        $this->info('[' . date('Y-m-d H:i:s') . '] Proses penarikan email selesai.');
    }

    private function extractBody($inbox, $email_number, $structure)
    {
        if (!isset($structure->parts)) {
            $pesan = imap_fetchbody($inbox, $email_number, 1);
            $encoding = $structure->encoding ?? 0;
            if ($encoding == 3) $pesan = base64_decode($pesan);
            elseif ($encoding == 4) $pesan = quoted_printable_decode($pesan);
            return $pesan;
        }

        $texts = $this->getEmailBodyText($inbox, $email_number, $structure->parts);
        if (!empty(trim($texts['plain']))) {
            return trim($texts['plain']);
        } elseif (!empty(trim($texts['html']))) {
            return strip_tags($texts['html']);
        }
        return "";
    }

    private function getEmailBodyText($inbox, $email_number, $parts, $part_number = "")
    {
        $texts = ['plain' => '', 'html' => ''];
        if (isset($parts) && count($parts)) {
            foreach ($parts as $i => $part) {
                $prefix = ($part_number) ? $part_number . "." . ($i + 1) : ($i + 1);
                if (isset($part->parts)) {
                    $subTexts = $this->getEmailBodyText($inbox, $email_number, $part->parts, $prefix);
                    $texts['plain'] .= $subTexts['plain'];
                    $texts['html'] .= $subTexts['html'];
                } else {
                    $is_attachment = false;
                    if ($part->ifdparameters) {
                        foreach ($part->dparameters as $obj) {
                            if (strtolower($obj->attribute) == 'filename') $is_attachment = true;
                        }
                    }
                    if ($part->ifparameters) {
                        foreach ($part->parameters as $obj) {
                            if (strtolower($obj->attribute) == 'name') $is_attachment = true;
                        }
                    }
                    
                    if (!$is_attachment) {
                        if ($part->subtype == 'PLAIN') {
                            $content = imap_fetchbody($inbox, $email_number, $prefix);
                            if ($part->encoding == 3) $content = base64_decode($content);
                            elseif ($part->encoding == 4) $content = quoted_printable_decode($content);
                            $texts['plain'] .= $content;
                        } elseif ($part->subtype == 'HTML') {
                            $content = imap_fetchbody($inbox, $email_number, $prefix);
                            if ($part->encoding == 3) $content = base64_decode($content);
                            elseif ($part->encoding == 4) $content = quoted_printable_decode($content);
                            $texts['html'] .= $content;
                        }
                    }
                }
            }
        }
        return $texts;
    }

    private function extractAttachments($inbox, $email_number, $structure)
    {
        if (isset($structure->parts)) {
            return $this->getAttachments($inbox, $email_number, $structure->parts);
        }
        return [];
    }

    private function getAttachments($inbox, $email_number, $parts, $part_number = "")
    {
        $attachments = [];
        if (isset($parts) && count($parts)) {
            foreach ($parts as $i => $part) {
                $prefix = ($part_number) ? $part_number . "." . ($i + 1) : ($i + 1);
                if (isset($part->parts)) {
                    $attachments = array_merge($attachments, $this->getAttachments($inbox, $email_number, $part->parts, $prefix));
                } else {
                    $is_attachment = false;
                    $filename = '';
                    if ($part->ifdparameters) {
                        foreach ($part->dparameters as $object) {
                            if (strtolower($object->attribute) == 'filename') {
                                $is_attachment = true;
                                $filename = $object->value;
                            }
                        }
                    }
                    if ($part->ifparameters) {
                        foreach ($part->parameters as $object) {
                            if (strtolower($object->attribute) == 'name') {
                                $is_attachment = true;
                                $filename = $object->value;
                            }
                        }
                    }
                    if ($is_attachment) {
                        $attachment = imap_fetchbody($inbox, $email_number, $prefix);
                        if ($part->encoding == 3) $attachment = base64_decode($attachment);
                        elseif ($part->encoding == 4) $attachment = quoted_printable_decode($attachment);
                        $attachments[] = [
                            'filename' => $filename,
                            'data' => $attachment
                        ];
                    }
                }
            }
        }
        return $attachments;
    }
}
