<!DOCTYPE html>
<html>

<head>
    <title>Input Skor Pertandingan</title>
    <!-- Link Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.min.css">
    <style>
        /* Custom CSS untuk tampilan form */
        body {
            background-color: #f8f9fa;
            /* Warna latar belakang halaman */
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .container h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #007bff;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-group input[type="number"],
        .form-group select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }

        .form-group input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .btn-back {
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Input Skor Pertandingan</h1>
        <form action="<?= base_url('klasemen/process_input_score') ?>" method="post">
            <div class="form-group">
                <label for="club1_id">Nama Klub Pertama:</label>
                <select name="club1_id" id="club1_id" class="form-control" required>
                    <option value="" disabled selected>Pilih Klub Pertama</option>
                    <?php foreach ($clubs as $club): ?>
                        <option value="<?= $club['id'] ?>"><?= $club['club_name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="club2_id">Nama Klub Kedua:</label>
                <select name="club2_id" id="club2_id" class="form-control" required>
                    <option value="" disabled selected>Pilih Klub Kedua</option>
                    <?php foreach ($clubs as $club): ?>
                        <option value="<?= $club['id'] ?>"><?= $club['club_name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="score1">Skor Pertama:</label>
                <input type="number" id="score1" name="score1" class="form-control" required min="0">
            </div>
            <div class="form-group">
                <label for="score2">Skor Kedua:</label>
                <input type="number" id="score2" name="score2" class="form-control" required min="0">
            </div>
            <div class="form-group text-center">
                <input type="submit" value="Simpan Skor" class="btn btn-primary" id="submitBtn">
            </div>
        </form>
        <div class="btn-back">
            <a href="<?= base_url('klasemen') ?>" class="btn btn-secondary btn-sm">Kembali ke Klasemen</a>
        </div>
    </div>

    <!-- Link Bootstrap 5 JavaScript (Optional) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- Link SweetAlert JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.all.min.js"></script>

    <script>
        document.getElementById('submitBtn').addEventListener('click', function (event) {
            event.preventDefault();
            const form = event.target.form;
            const club1_id = document.getElementById('club1_id').value;
            const club2_id = document.getElementById('club2_id').value;
            const score1 = document.getElementById('score1').value;
            const score2 = document.getElementById('score2').value;

            // Validasi jika ada input yang kosong
            if (club1_id.trim() === '' || club2_id.trim() === '' || score1.trim() === '' || score2.trim() === '') {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Harap isi semua kolom form!',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
            } else {
                // Kirim fetch request untuk memeriksa apakah pertandingan antara kedua klub sudah ada
                fetch('<?= base_url('klasemen/check_existing_match') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-type': 'application/x-www-form-urlencoded'
                    },
                    body: `club1_id=${encodeURIComponent(club1_id)}&club2_id=${encodeURIComponent(club2_id)}`
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            Swal.fire({
                                title: 'Peringatan!',
                                text: 'Pertandingan antara kedua klub sudah ada!',
                                icon: 'warning',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            Swal.fire({
                                title: 'Simpan Skor',
                                text: 'Apakah Anda yakin ingin menyimpan skor pertandingan?',
                                icon: 'info',
                                showCancelButton: true,
                                confirmButtonText: 'Ya, Simpan!',
                                cancelButtonText: 'Batal'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    form.submit();
                                }
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat memeriksa data. Silakan coba lagi.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
            }
        });
    </script>

</body>

</html>