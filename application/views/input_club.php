<!DOCTYPE html>
<html>

<head>
    <title>Input Data Klub</title>
    <!-- Link Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <!-- Link SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.min.css">
    <style>
        /* Custom CSS untuk tampilan form */
        body {
            background-color: #f0f0f0;
            /* Background color halaman */
        }

        .container {
            max-width: 400px;
            margin-top: 50px;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            background-color: #fff;
        }

        .container h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-group input {
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
            display: block;
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Input Data Klub</h1>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger" role="alert">
                <?= $this->session->flashdata('error') ?>
            </div>
        <?php endif; ?>
        <form action="<?= base_url('klasemen/process_input_club') ?>" method="post" id="clubForm">
            <div class="form-group">
                <label for="club_name">Nama Klub:</label>
                <input type="text" id="club_name" name="club_name" class="form-control" required>
                <?php echo form_error('club_name', '<div class="text-danger">', '</div>') ?>
            </div>
            <div class="form-group">
                <label for="club_city">Kota Klub:</label>
                <input type="text" id="club_city" name="club_city" class="form-control" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Tambahkan" class="btn btn-primary" id="submitBtn">
            </div>
        </form>

        <div class="btn-back">
            <a href="<?= base_url('klasemen') ?>" class="btn btn-sm btn-secondary">Kembali ke Klasemen</a>
        </div>
    </div>

    <!-- Link Bootstrap 5 JavaScript (Optional) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- Link SweetAlert JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.all.min.js"></script>

    <!-- Script untuk menampilkan SweetAlert -->
    <script>
        document.getElementById('submitBtn').addEventListener('click', function (event) {
            event.preventDefault();
            const form = event.target.form;
            const clubName = document.getElementById('club_name').value;
            const clubCity = document.getElementById('club_city').value;

            // Validasi jika ada input yang kosong
            if (clubName.trim() === '' || clubCity.trim() === '') {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Harap isi semua kolom form!',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
            } else {
                // Kirim AJAX request untuk memeriksa apakah klub dengan nama yang sama sudah ada
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '<?= base_url('klasemen/check_existing_club') ?>', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.exists) {
                            Swal.fire({
                                title: 'Peringatan!',
                                text: 'Nama klub sudah ada',
                                icon: 'warning',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            Swal.fire({
                                title: 'Simpan Data Klub',
                                text: 'Apakah Anda yakin ingin menyimpan data klub?',
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
                    }
                };
                xhr.send(`club_name=${encodeURIComponent(clubName)}`);
            }
        });

        // Tampilkan SweetAlert jika ada pesan sukses dari server-side
        <?php if ($this->session->flashdata('success')): ?>
            Swal.fire({
                title: 'Sukses',
                text: '<?= $this->session->flashdata('success') ?>',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>
    </script>
</body>

</html>