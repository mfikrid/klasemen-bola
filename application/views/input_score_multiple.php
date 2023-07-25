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
            max-width: 800px;
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

        .add-score-btn {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        .add-score-btn:hover {
            background-color: #218838;
        }

        .btn-back {
            text-align: center;
            margin-top: 15px;
        }

        .score-row {
            margin-bottom: 15px;
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
        <form id="scoreForm" action="<?= base_url('klasemen/process_input_score_multiple') ?>" method="post">
            <div id="scoresContainer">
                <!-- Default score row -->
                <div class="score-row row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="club1_id">Nama Klub Pertama:</label>
                            <select name="club1_id[]" class="form-control" required>
                                <option value="" disabled selected>Pilih Klub Pertama</option>
                                <?php foreach ($clubs as $club): ?>
                                    <option value="<?= $club['id'] ?>"><?= $club['club_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="club2_id">Nama Klub Kedua:</label>
                            <select name="club2_id[]" class="form-control" required>
                                <option value="" disabled selected>Pilih Klub Kedua</option>
                                <?php foreach ($clubs as $club): ?>
                                    <option value="<?= $club['id'] ?>"><?= $club['club_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="score1">Skor Pertama:</label>
                            <input type="number" name="score1[]" class="form-control" required min="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="score2">Skor Kedua:</label>
                            <input type="number" name="score2[]" class="form-control" required min="0">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group btn-container text-center">
                <button type="button" class="add-score-btn" id="addScoreBtn">Tambah Skor</button>
                <button type="button" class="add-score-btn" id="submitBtn">Simpan Skor</button>
            </div>
            <div class="btn-back">
                <a href="<?= base_url('klasemen') ?>" class="btn btn-secondary btn-sm">Kembali ke Klasemen</a>
            </div>
        </form>
    </div>

    <!-- Link Bootstrap 5 JavaScript (Optional) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- Link SweetAlert JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Event handler for "Tambah Skor" button
            $("#addScoreBtn").click(function () {
                // Clone the default score row and append it to the container
                var newScoreRow = $(".score-row").eq(0).clone();
                $("#scoresContainer").append(newScoreRow);

                // Reset the input fields in the cloned row
                newScoreRow.find("input[type='number']").val("");
                newScoreRow.find("select").prop("selectedIndex", 0);
            });

            // Event handler for form submission with additional match check
            $("#submitBtn").click(function (event) {
                event.preventDefault(); // Prevent form submission

                // Collect club IDs and scores
                var club1_ids = $("select[name='club1_id[]']").map(function () {
                    return this.value;
                }).get();
                var club2_ids = $("select[name='club2_id[]']").map(function () {
                    return this.value;
                }).get();

                // Check if any match already exists
                for (var i = 0; i < club1_ids.length; i++) {
                    if (club1_ids[i] !== "" && club2_ids[i] !== "" && club1_ids[i] === club2_ids[i]) {
                        Swal.fire({
                            title: 'Peringatan!',
                            text: 'Tim tidak dapat melawan tim dengan nama yang sama!',
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });
                        return; // Stop further processing
                    }

                    if (club1_ids[i] !== "" && club2_ids[i] !== "") {
                        checkExistingMatch(club1_ids[i], club2_ids[i]);
                        return; // Stop further processing
                    }
                }

                // If there are no matches to check, proceed to submit the form
                $("#scoreForm").submit();
            });

            function checkExistingMatch(club1_id, club2_id) {
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
                                    // Jika user mengonfirmasi, submit form
                                    $("#scoreForm").submit();
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