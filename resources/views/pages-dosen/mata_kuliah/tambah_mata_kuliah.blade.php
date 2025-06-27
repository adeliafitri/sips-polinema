@extends('layouts.dosen.main')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bs-stepper/dist/css/bs-stepper.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Data Mata Kuliah</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dosen.matakuliah') }}">Data Mata Kuliah</a></li>
                        <li class="breadcrumb-item active">Tambah Data</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="col-12 justify-content-center">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <div class="card-header d-flex justify-content-end">
                            <h3 class="card-title col align-self-center">Form Tambah Data Mata Kuliah</h3>
                            <!-- <div class="col-sm-2">
                                <a href="index.php?include=data-mahasiswa" class="btn btn-warning"><i class="nav-icon fas fa-arrow-left mr-2"></i> Kembali</a>
                                </div> -->
                        </div>
                        <div class="card-body">
                            <div class="container">
                                <form action="{{ route('dosen.matakuliah.create.matkul') }}" method="post"
                                    enctype="multipart/form-data" id="myForm">
                                    @CSRF
                                    <div id="test-l-1" class="content">
                                        <div class="form-group">
                                            <label for="kode_matkul">Kode Matkul</label>
                                            <input type="text" class="form-control" id="kode_matkul"
                                                name="kode_matkul" placeholder="Kode Mata Kuliah" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="nama_matkul">Nama Matkul</label>
                                            <input type="text" class="form-control" id="nama_matkul"
                                                name="nama_matkul" placeholder="Nama Mata Kuliah">
                                        </div>
                                        <div class="form-group">
                                            <label for="sks">SKS</label>
                                            <input type="number" class="form-control" id="sks" name="sks"
                                                placeholder="SKS">
                                        </div>

                                        <a href="{{ route('dosen.matakuliah') }}" class="btn btn-default">Cancel</a>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <script src="dist/js/bs-stepper.js"></script>
                        <script>
                            var stepper1Node = document.querySelector('#stepper1');
                            var stepper1 = new Stepper(document.querySelector('#stepper1'));
                            var saveAndNextBtn = document.getElementById('saveAndNextBtn');

                            stepper1Node.addEventListener('show.bs-stepper', function(event) {
                                console.warn('show.bs-stepper', event);
                            });
                            stepper1Node.addEventListener('shown.bs-stepper', function(event) {
                                console.warn('shown.bs-stepper', event);
                            });

                            // Array to store data
                            let dataArray = [];
                            // Function to handle button click
                            function handleButton1Click() {
                                // Check if the form is empty
                                if (isFormEmpty()) {
                                    alert("Form is empty. Please fill in the fields.");
                                } else {
                                    // Add data if form is not empty
                                    addData();

                                    // Clear the form
                                    clearForm();
                                }
                            }

                            function handleButton2Click() {
                                if (isFormEmpty()) {
                                    alert("Form is empty. Please fill in the fields.");
                                } else {
                                    // Add data if form is not empty
                                    addData2();

                                    // Clear the form
                                    clearForm();
                                }
                            }

                            function handleButton3Click() {
                                if (isFormEmpty()) {
                                    alert("Form is empty. Please fill in the fields.");
                                } else {
                                    // Add data if form is not empty
                                    addData3();

                                    // Clear the form
                                    clearForm();
                                }
                            }

                            // Function to check if form is empty
                            function isFormEmpty() {
                                let cpl_id = document.getElementById("cpl_id").value.trim();
                                let kode_cpmk = document.getElementById("kode_cpmk").value.trim();
                                let deskripsi_cpmk = document.getElementById("deskripsi_cpmk").value.trim();
                                let pilih_cpmk = document.getElementById("pilih_cpmk").value.trim();
                                let kode_subcpmk = document.getElementById("kode_subcpmk").value.trim();
                                let deskripsi_subcpmk = document.getElementById("deskripsi_subcpmk").value.trim();
                                let pilih_subcpmk = document.getElementById("pilih_subcpmk").value.trim();
                                let bentuk_soal = document.getElementById("bentuk_soal").value.trim();
                                let bobot = document.getElementById("bobot").value.trim();
                                let waktu_pelaksanaan = document.getElementById("waktu_pelaksanaan").value.trim();

                                return cpl_id === "" && kode_cpmk === "" && deskripsi_cpmk === "" &&
                                        pilih_cpmk === "" && kode_subcpmk === "" && deskripsi_subcpmk === "" &&
                                        pilih_subcpmk === "" && bentuk_soal === "" && bobot === "" && waktu_pelaksanaan === "";
                            }

                            // Function to add data 1
                            function addData() {
                            // Get values from the form
                                //step 2
                                let cpl_id = document.getElementById("cpl_id").value;
                                let kode_cpmk = document.getElementById("kode_cpmk").value;
                                let deskripsi_cpmk = document.getElementById("deskripsi_cpmk").value;
                                const newOptionInput = document.getElementById('kode_cpmk');
                                const dynamicDropdown = document.getElementById('pilih_cpmk');
                                const newOptionValue = newOptionInput.value.trim();

                                    if (newOptionValue !== '') {
                                        // Check if the option already exists
                                        if (!dynamicDropdown.querySelector(`option[value="${newOptionValue}"]`)) {
                                            // Create a new option element
                                            const newOption = document.createElement('option');
                                            newOption.value = newOptionValue;
                                            newOption.text = newOptionValue;

                                            // Add the new option to the dropdown
                                            dynamicDropdown.add(newOption);

                                            // Clear the input
                                            newOptionInput.value = '';
                                        }
                                    //     else {
                                    //         alert('Option already exists.');
                                    //     }
                                    // } else {
                                    //     alert('Please enter a valid option.');
                                    }

                                // Push data into the array
                                dataArray.push({ cpl_id, kode_cpmk, deskripsi_cpmk });

                                // Display the array in a table
                                displayData();

                                // Clear the form
                                clearForm();
                            }

                            // Function to add data 2
                            function addData2() {
                            // Get values from the form
                                //step 2
                                let pilih_cpmk = document.getElementById("pilih_cpmk").value;
                                let kode_subcpmk = document.getElementById("kode_subcpmk").value;
                                let deskripsi_subcpmk = document.getElementById("deskripsi_subcpmk").value;
                                const newOptionInput = document.getElementById('kode_subcpmk');
                                const dynamicDropdown = document.getElementById('pilih_subcpmk');
                                const newOptionValue = newOptionInput.value.trim();

                                    if (newOptionValue !== '') {
                                        // Check if the option already exists
                                        if (!dynamicDropdown.querySelector(`option[value="${newOptionValue}"]`)) {
                                            // Create a new option element
                                            const newOption = document.createElement('option');
                                            newOption.value = newOptionValue;
                                            newOption.text = newOptionValue;

                                            // Add the new option to the dropdown
                                            dynamicDropdown.add(newOption);

                                            // Clear the input
                                            newOptionInput.value = '';
                                        }
                                    //     else {
                                    //         alert('Option already exists.');
                                    //     }
                                    // } else {
                                    //     alert('Please enter a valid option.');
                                    }

                                // Push data into the array
                                dataArray.push({ pilih_cpmk, kode_subcpmk, deskripsi_subcpmk });

                                // Display the array in a table
                                displayData2();

                                // Clear the form
                                clearForm();
                            }

                            // Function to add data 3
                            function addData3() {
                            // Get values from the form
                                //step 2
                                let pilih_subcpmk = document.getElementById("pilih_subcpmk").value;
                                let bentuk_soal = document.getElementById("bentuk_soal").value;
                                let bobot = document.getElementById("bobot").value;
                                let waktu_pelaksanaan = document.getElementById("waktu_pelaksanaan").value;

                                // Push data into the array
                                dataArray.push({ pilih_subcpmk, bentuk_soal, bobot, waktu_pelaksanaan });

                                // Display the array in a table
                                displayData3();

                                // Clear the form
                                clearForm();
                            }

                            // Function to clear the form
                            function clearForm() {
                                document.getElementById("cpl_id").value = "";
                                document.getElementById("kode_cpmk").value = "";
                                document.getElementById("deskripsi_cpmk").value = "";
                                document.getElementById("pilih_cpmk").value = "";
                                document.getElementById("kode_subcpmk").value = "";
                                document.getElementById("deskripsi_subcpmk").value = "";
                                document.getElementById("pilih_subcpmk").value = "";
                                document.getElementById("bentuk_soal").value = "";
                                document.getElementById("bobot").value = "";
                                document.getElementById("waktu_pelaksanaan").value = "";
                            }

                            // Function to display data in a table 1
                            function displayData() {
                                let dataTable = document.getElementById("dataTable");
                                let tbody = document.getElementById("dataArray");
                                tbody.innerHTML = ''; // Clear previous table

                                // Create table rows for each data entry
                                dataArray.forEach((data, index) => {
                                    let row = tbody.insertRow();
                                    let cellNumber = row.insertCell(0);
                                    let cellCpl_id = row.insertCell(1);
                                    let cellKode_cpmk = row.insertCell(2);
                                    let cellDeskripsi_cpmk = row.insertCell(3);
                                    let cellAction = row.insertCell(4);

                                    cellNumber.textContent = index + 1;
                                    cellCpl_id.textContent = data.cpl_id;
                                    cellKode_cpmk.textContent = data.kode_cpmk;
                                    cellDeskripsi_cpmk.textContent = data.deskripsi_cpmk;

                                    // Add delete button with onclick event
                                    let deleteButton = document.createElement("button");
                                    deleteButton.innerHTML = '<i class="fas fa-trash"></i>';
                                    deleteButton.classList.add("btn", "btn-danger");
                                    deleteButton.onclick = function () {
                                    deleteData(index);
                                    };
                                    cellAction.appendChild(deleteButton);
                                });
                            }

                            // Function to display data in a table 2
                            function displayData2() {
                                let dataTable = document.getElementById("dataTable2");
                                let tbody = document.getElementById("dataArray2");
                                tbody.innerHTML = ''; // Clear previous table

                                // Create table rows for each data entry
                                dataArray.forEach((data, index) => {
                                    let row = tbody.insertRow();
                                    let cellNumber = row.insertCell(0);
                                    let cellPilih_cpmk = row.insertCell(1);
                                    let cellKode_subcpmk = row.insertCell(2);
                                    let cellDeskripsi_subcpmk = row.insertCell(3);
                                    let cellAction = row.insertCell(4);

                                    cellNumber.textContent = index + 1;
                                    cellPilih_cpmk.textContent = data.pilih_cpmk;
                                    cellKode_subcpmk.textContent = data.kode_subcpmk;
                                    cellDeskripsi_subcpmk.textContent = data.deskripsi_subcpmk;

                                    // Add delete button with onclick event
                                    let deleteButton = document.createElement("button");
                                    deleteButton.innerHTML = '<i class="fas fa-trash"></i>';
                                    deleteButton.classList.add("btn", "btn-danger");
                                    deleteButton.onclick = function () {
                                    deleteData(index);
                                    };
                                    cellAction.appendChild(deleteButton);
                                });
                            }

                            // Function to display data in a table 3
                            function displayData3() {
                                let dataTable = document.getElementById("dataTable3");
                                let tbody = document.getElementById("dataArray3");
                                tbody.innerHTML = ''; // Clear previous table

                                // Create table rows for each data entry
                                dataArray.forEach((data, index) => {
                                    let row = tbody.insertRow();
                                    let cellNumber = row.insertCell(0);
                                    let cellPilih_subcpmk = row.insertCell(1);
                                    let cellBentuk_soal = row.insertCell(2);
                                    let cellBobot = row.insertCell(3);
                                    let cellWaktu_pelaksanaan = row.insertCell(4);
                                    let cellAction = row.insertCell(5);

                                    cellNumber.textContent = index + 1;
                                    cellPilih_subcpmk.textContent = data.pilih_subcpmk;
                                    cellBentuk_soal.textContent = data.bentuk_soal;
                                    cellBobot.textContent = data.bobot;
                                    cellWaktu_pelaksanaan.textContent = data.waktu_pelaksanaan;

                                    // Add delete button with onclick event
                                    let deleteButton = document.createElement("button");
                                    deleteButton.innerHTML = '<i class="fas fa-trash"></i>';
                                    deleteButton.classList.add("btn", "btn-danger");
                                    deleteButton.onclick = function () {
                                    deleteData(index);
                                    };
                                    cellAction.appendChild(deleteButton);
                                });
                            }

                                // Function to delete data
                                function deleteData(index) {
                                    dataArray.splice(index, 1);
                                    displayData(), displayData2(), displayData3();
                            }

                        </script>
                    </div><!-- /.card -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </section><!-- /.content -->
@endsection
