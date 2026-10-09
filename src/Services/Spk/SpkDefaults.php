<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services\Spk;

/**
 * Default Pasal texts (Markdown) for a new SPK, from the PT Anugerah Berkat Sarana SPK.
 * Free-text "continuation" fields (duration notes, payment notes, correspondence text) continue the
 * Pasal's ayat numbering: each top-level paragraph or list item becomes the next ayat.
 */
final class SpkDefaults
{
    public const ASAS = 'Kerja Sama ini berlandaskan pada iktikad baik dengan menegakkan prinsip-prinsip saling percaya, persamaan derajat, saling menguntungkan, dengan tetap menundukkan diri pada ketentuan yang berlaku dan selalu berusaha menyelesaikan masalah yang mungkin timbul dengan cara profesional untuk mencapai musyawarah mufakat.';

    public const WORK_START = 'terhitung dimulai sejak tanggal pembayaran tahap 1 (DP) dibayarkan oleh PIHAK PERTAMA';

    public const OBLIGATIONS_FIRST = <<<'MD'
1. Menyediakan dan menyerahkan materi untuk tujuan publikasi kepada PIHAK KEDUA.
2. Menyediakan file softcopy baik berbentuk dokumen maupun image yang diperlukan selama pekerjaan ini berlangsung kepada PIHAK KEDUA dalam bentuk file elektronik/softcopy (docx, pptx, pdf, jpg, tif, png, bmp, atau psd) atau tercetak (dengan kualitas memadai untuk di-scan).
3. Mempersiapkan content/isi yang diperlukan, meliputi data informasi dan dokumen.
4. Menyediakan satu orang koordinator yang bertanggung jawab untuk pengambilan keputusan tentang pekerjaan dalam hal: visual desain, materi, proses bisnis dan testing.
5. Membuat Key Performance Indicator (KPI) untuk bersama-sama disepakati oleh KEDUA PIHAK.
6. Melakukan evaluasi terhadap performa layanan PIHAK KEDUA berdasarkan ruang lingkup pekerjaan dan KPI yang telah disepakati bersama.
MD;

    public const OBLIGATIONS_SECOND = <<<'MD'
1. Ruang lingkup pekerjaan PIHAK KEDUA ialah sebagaimana yang disebut dalam Pasal 2 Perjanjian ini.
2. PIHAK KEDUA wajib memberikan jasa Maintenance yang meliputi:
   1. Perbaikan bug/error Aplikasi
   2. Dukungan penggunaan Aplikasi
3. Menyerahkan source code untuk kepentingan perawatan dan backup di PIHAK PERTAMA dan tidak untuk diperjualbelikan.
4. Membuat laporan hasil pekerjaan yang selanjutnya dituangkan dalam dokumen Berita Acara Serah Terima (BAST) untuk ditandatangani oleh Para Pihak.
5. PIHAK KEDUA wajib menjaga kerahasiaan atas seluruh informasi yang didapat dari PIHAK PERTAMA dan tidak dibenarkan untuk membuka rahasia tersebut dengan cara apa pun, baik sebagian maupun seluruhnya kepada pihak lainnya tanpa persetujuan tertulis dari PIHAK PERTAMA. Tidak satu pun informasi diperkenankan untuk dipergunakan oleh PIHAK KEDUA untuk keperluan di luar keperluan dari Perjanjian ini.
MD;

    public const SERVER = <<<'MD'
1. Layanan Sewa Server Hosting dan Domain, free untuk 1 (satu) tahun pertama, terhitung dari pembayaran awal (down payment).
2. Biaya Layanan Sewa Server Hosting untuk tahun berikutnya adalah Rp2.000.000/bulan, dibayarkan maksimal 14 hari kalender setelah diterbitkannya invoice.
3. Biaya Domain (top level domain .com) untuk tahun berikutnya adalah Rp400.000/tahun, dibayarkan maksimal 14 hari kalender setelah diterbitkannya invoice.
MD;

    public const CONFIDENTIALITY = <<<'MD'
1. Mengenai kerahasiaan yang timbul akibat perjanjian ini PARA PIHAK tunduk pada Undang-Undang Republik Indonesia No. 30 Tahun 2000 mengenai Rahasia Dagang atau ketentuan terkait yang lebih baru.
2. Kecuali pada hal-hal yang diperbolehkan secara hukum, PARA PIHAK setuju untuk tidak menggandakan informasi rahasia yang dimiliki oleh pihak lain atau mengungkapkan kepada pihak ketiga mana pun untuk tujuan apa pun kecuali untuk pelaksanaan Perjanjian ini. Salah satu pihak akan segera memberitahukan pihak lainnya apabila diperkirakan adanya kebocoran informasi rahasia atau penggunaan yang tidak sah, penggandaan yang tidak sepatutnya, atau pengungkapan bentuk lain mana pun terhadap informasi rahasia.
3. Publikasi bentuk apa pun atau pernyataan publik lainnya oleh salah satu pihak, baik langsung maupun tidak langsung secara lisan maupun tulisan atau elektronik terhadap hal apa pun berkaitan dengan Perjanjian ini, wajib mendapat persetujuan tertulis terlebih dahulu dari pihak lainnya. Tanpa batasan pada kebiasaan yang ada, tidak satu pun publikasi yang berhubungan dengan Perjanjian ini akan dikeluarkan ke media cetak lain mana pun tanpa persetujuan tertulis dari PARA PIHAK.
4. Dalam hal pengakhiran Perjanjian maka ketentuan dalam pasal ini tetap berlaku, namun demikian seluruh batasan di atas tidak berlaku apabila informasi rahasia yang dimaksud sudah menjadi informasi umum yang mana perolehannya secara sah menurut hukum oleh PARA PIHAK.
MD;

    public const DISPUTE = <<<'MD'
1. Jika terjadi perselisihan antara PARA PIHAK, maka pada dasarnya akan diselesaikan secara musyawarah.
2. Apabila penyelesaian secara musyawarah tidak dapat dicapai, PARA PIHAK sepakat untuk diselesaikan dalam tingkat pertama dan terakhir menurut peraturan prosedur Badan Arbitrase Nasional Indonesia (BANI) di Jakarta oleh arbiter yang ditunjuk menurut peraturan tersebut. Keputusan BANI tersebut adalah final dan mengikat PARA PIHAK.
MD;

    /** Continues after the generated ayat 1 (the contact tables). */
    public const CORRESPONDENCE = <<<'MD'
1. Surat-menyurat ke alamat tersebut dianggap telah diterima dengan ketentuan sebagai berikut:
   1. Pada hari yang sama jika diserahkan langsung yang dibuktikan dengan tanda tangan penerima pada buku pengantar surat atau tanda terima lain yang diterbitkan oleh pengirim.
   2. Pada hari yang sama jika pemberitahuan tersebut dikirimkan melalui faksimili/email dengan hasil baik.
   3. Pada hari kerja kelima jika pemberitahuan tersebut dikirimkan per pos yang dibuktikan dengan resi pengiriman tercatat.
2. Dalam hal terjadi perubahan alamat dari alamat tersebut di atas atau alamat terakhir yang tercatat pada Para Pihak, maka perubahan tersebut harus diberitahukan secara tertulis kepada pihak lain dalam Perjanjian ini selambat-lambatnya 5 (lima) hari kerja sebelum perubahan alamat dimaksud berlaku efektif. Jika perubahan tersebut tidak diberitahukan berdasarkan Perjanjian ini, maka korespondensi dianggap telah diberikan dengan semestinya dengan tata cara sebagaimana dimaksud dalam ayat 2 pasal ini.
MD;

    public const MISC = <<<'MD'
1. Perjanjian Kerja Sama ini akan ditinjau dan dikaji ulang apabila salah satu dan atau Para Pihak merasa perlu untuk diadakan penyesuaian dengan situasi dan kondisi yang akan ditentukan kemudian.
2. Segala sesuatu yang belum diatur atau belum cukup diatur dalam Perjanjian ini, akan diatur kemudian dalam suatu addendum/amandemen yang akan merupakan satu kesatuan dan bagian yang tidak terpisahkan dari Perjanjian ini.
3. Keabsahan penafsiran dan pelaksanaan Perjanjian ini diatur dan tunduk pada hukum yang berlaku di negara Republik Indonesia.
MD;

    public const DP_LABEL = 'Pembayaran awal (down payment)';

    public const DP_DUE = 'dibayar pada saat Perjanjian ini ditandatangani';

    public const FINAL_LABEL = 'Pembayaran tahap akhir';

    public const FINAL_DUE = 'dibayarkan ketika semua pekerjaan telah selesai (officially launched) maksimal 14 hari kalender setelah diterbitkannya invoice';
}
