<!DOCTYPE html>
<html>

<head>
    <title>Klasemen</title>
    <!-- Link CSS DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <!-- Link Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">

    <!-- Link jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Link JavaScript DataTables -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <style>
        /* Custom CSS untuk tampilan tabel */
        body {
            background-color: #f0f0f0;
            /* Background color halaman */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: transparent;
            /* Background transparan untuk tabel */
            border: 1px solid #ccc;
            /* Garis outline untuk tabel */
        }

        th,
        td {
            padding: 8px;
            text-align: center;
            /* Teks berada di tengah kolom */
            border-bottom: 1px solid #ddd;
            /* Garis tabel berwarna */
            color: #000;
            /* Warna teks hitam */
        }

        th {
            background-color: #555;
            /* Warna latar header kolom */
            color: #fff;
            /* Warna teks putih pada header */
        }

        tbody tr:hover {
            background-color: #444;
            /* Efek hover pada baris */
        }

        .btn {
            margin-right: 10px;
        }

        .table-note {
            font-size: 12px;
            color: #555;
            text-align: left;
            margin-top: 5px;
        }

        /* Perapian untuk setiap hasil pertandingan */
        .win {
            background-color: #dff0d8;
            /* Warna latar hijau untuk menang */
        }

        .draw {
            background-color: #fcf8e3;
            /* Warna latar kuning untuk seri */
        }

        .lose {
            background-color: #f2dede;
            /* Warna latar merah untuk kalah */
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="mt-5 mb-4">Klasemen Liga 1 Indonesia 2023</h1>
        <table id="klasemenTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Klub</th>
                    <th>Ma</th>
                    <th>Me</th>
                    <th>S</th>
                    <th>Ka</th>
                    <th>GM</th>
                    <th>GK</th>
                    <th>Point</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($klasemen as $index => $data): ?>
                    <?php
                    // Tambahkan class CSS berdasarkan hasil pertandingan
                    $resultClass = '';
                    if ($data['win'] > 0) {
                        $resultClass = 'win';
                    } elseif ($data['draw'] > 0) {
                        $resultClass = 'draw';
                    } elseif ($data['lose'] > 0) {
                        $resultClass = 'loss';
                    }
                    ?>
                    <tr class="<?= $resultClass ?>">
                        <td>
                            <?= $index + 1 ?>
                        </td>
                        <td>
                            <?= $data['club_name'] ?>
                        </td>
                        <td>
                            <?= $data['played'] ?>
                        </td>
                        <td>
                            <?= isset($data['win']) ? $data['win'] : 0 ?>
                        </td>
                        <td>
                            <?= isset($data['draw']) ? $data['draw'] : 0 ?>
                        </td>
                        <td>
                            <?= isset($data['lose']) ? $data['lose'] : 0 ?>
                        </td>
                        <td>
                            <?= isset($data['goals_for']) ? $data['goals_for'] : 0 ?>
                        </td>
                        <td>
                            <?= isset($data['goals_against']) ? $data['goals_against'] : 0 ?>
                        </td>
                        <td>
                            <?= isset($data['points']) ? $data['points'] : 0 ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="table-note">
            <span>Keterangan:<br>
                Ma = Main <br> S = Seri <br> Ka = Kalah <br> GM = Gol Memasukkan <br>
                GK = Gol Kebobolan</span>
        </div>
        <div class="mt-3">
            <a href="<?= base_url('klasemen/input_club') ?>" class="btn btn-primary">Tambah Klub</a>
            <a href="<?= base_url('klasemen/input_score') ?>" class="btn btn-primary">Input Skor Pertandingan</a>
            <a href="<?= base_url('klasemen/input_score_multiple') ?>" class="btn btn-primary">Input Skor Multiple</a>
        </div>
    </div>

    <script>
        // Aktifkan DataTables
        $(document).ready(function () {
            $('#klasemenTable').DataTable({
                "paging": false,
                "lengthChange": false,
                "searching": false,
                "ordering": false,
                "info": false,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "emptyTable": "Tidak ada data",
                    "info": "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                    "infoEmpty": "Menampilkan 0 hingga 0 dari 0 entri",
                    "lengthMenu": "Tampilkan _MENU_ entri",
                    "search": "Cari:",
                    "zeroRecords": "Tidak ditemukan data yang sesuai"
                }
            });
        });
    </script>

    <!-- Link Bootstrap 5 JavaScript (Optional) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>