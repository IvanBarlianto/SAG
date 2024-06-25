@extends('index')
@section('title', 'Home')

@section('isihalaman')
    <h3>Pengertian perpustakaan menurut para ahli</h3>
    <p>
    <h4>Perpustakaan</h4>
        Perpustakaan Perguruan Tinggi merupakan unit kerja pelaksana teknis (UPT) Perguruan Tinggi yang bersama-sama
		dengan unit lain turut melaksanakan Tri Dharma Perguruan Tinggi, yaitu: pendidikan, penelitian, dan pengabdian
		kepada masyarakat dengan cara memilah, menghimpun, mengolah, merawat, serta menyebarluaskan sumber informasi kepada
		lembaga induknya pada khususnya dan sivitas akademika pada umumnya. Kelima tugas tersebut dilaksanakan dengan
		tata cara administrasi dan organisasi yang berlaku bagi penyelenggaraan sebuah perpustakaan.
    <p>
    <h4>Perpustakaan SAG</h4>
    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras consectetur vehicula congue. Praesent nec augue at nisl
    scelerisque ultrices a a mauris. Mauris condimentum turpis ligula, eleifend consectetur velit eleifend porta. Praesent
    eu neque bibendum, pretium est ac, pulvinar enim. Sed rutrum vestibulum posuere. Aenean faucibus mauris nibh, nec lobortis
    urna lobortis in. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Sed egestas
    nibh erat, ac vulputate eros gravida ut. In iaculis iaculis blandit. Curabitur a dignissim sem, eu iaculis ex. Vestibulum in
    lacinia tortor. Donec ornare velit sit amet ante facilisis, eget accumsan dui auctor.
    
    <div class="container">
        <h3 class="text-center">Daftar Buku Perpustakaan SAG</h3>
    
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalBukuTambah">
            Tambah Data Buku
        </button>
    
        <p></p>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <td align="center">No</td>
                    <td align="center">ID Buku</td>
                    <td align="center">Kode Buku</td>
                    <td align="center">Judul Buku</td>
                    <td align="center">Pengarang</td>
                    <td align="center">Kategori</td>
                    <td align="center">Aksi</td>
                </tr>
            </thead>
            <tbody id="bookTableBody">
            </tbody>
        </table>
    
        <!-- Modal Tambah Data Buku -->
        <div class="modal fade" id="modalBukuTambah" tabindex="-1" role="dialog" aria-labelledby="modalBukuTambahLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalBukuTambahLabel">Form Input Data Buku</h5>
                    </div>
                    <div class="modal-body">
                        <form id="formbukutambah">
                            <div class="form-group row">
                                <label for="kode_buku" class="col-sm-4 col-form-label">Kode Buku</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="kode_buku" name="kode_buku" placeholder="Masukan Kode Buku" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="judul" class="col-sm-4 col-form-label">Judul Buku</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="judul" name="judul" placeholder="Masukan Judul Buku" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="pengarang" class="col-sm-4 col-form-label">Nama Pengarang</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="pengarang" name="pengarang" placeholder="Masukan Nama Pengarang" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kategori" class="col-sm-4 col-form-label">Kategori</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="kategori" name="kategori" placeholder="Masukan Kategori" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-success">Tambah</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Akhir Modal Tambah Data Buku -->
    
        <!-- Modal Edit Data Buku -->
        <div class="modal fade" id="modalBukuEdit" tabindex="-1" role="dialog" aria-labelledby="modalBukuEditLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalBukuEditLabel">Form Edit Data Buku</h5>
                    </div>
                    <div class="modal-body">
                        <form id="formbukuedit">
                            <input type="hidden" id="edit_id_buku">
                            <div class="form-group row">
                                <label for="edit_kode_buku" class="col-sm-4 col-form-label">Kode Buku</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="edit_kode_buku" name="edit_kode_buku" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="edit_judul" class="col-sm-4 col-form-label">Judul Buku</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="edit_judul" name="edit_judul" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="edit_pengarang" class="col-sm-4 col-form-label">Nama Pengarang</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="edit_pengarang" name="edit_pengarang" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="edit_kategori" class="col-sm-4 col-form-label">Kategori</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="edit_kategori" name="edit_kategori" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-success">Edit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Akhir Modal Edit Data Buku -->
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <script>
        let books = JSON.parse(localStorage.getItem('books')) || [];
    
        const renderBooks = () => {
            const bookTableBody = document.getElementById('bookTableBody');
            bookTableBody.innerHTML = '';
            books.forEach((book, index) => {
                bookTableBody.innerHTML += `
                    <tr>
                        <td align="center">${index + 1}</td>
                        <td>${book.id}</td>
                        <td>${book.kode}</td>
                        <td>${book.judul}</td>
                        <td>${book.pengarang}</td>
                        <td>${book.kategori}</td>
                        <td align="center">
                            <button type="button" class="btn btn-warning" onclick="openEditModal(${book.id})">
                                Edit
                            </button>
                            <button class="btn btn-danger" onclick="deleteBook(${book.id})">
                                Delete
                            </button>
                        </td>
                    </tr>
                `;
            });
        };
    
        const openEditModal = (id) => {
            const book = books.find(b => b.id === id);
            document.getElementById('edit_id_buku').value = book.id;
            document.getElementById('edit_kode_buku').value = book.kode;
            document.getElementById('edit_judul').value = book.judul;
            document.getElementById('edit_pengarang').value = book.pengarang;
            document.getElementById('edit_kategori').value = book.kategori;
            $('#modalBukuEdit').modal('show');
        };
    
        document.getElementById('formbukutambah').addEventListener('submit', (e) => {
            e.preventDefault();
            const id = new Date().getTime();
            const kode = document.getElementById('kode_buku').value;
            const judul = document.getElementById('judul').value;
            const pengarang = document.getElementById('pengarang').value;
            const kategori = document.getElementById('kategori').value;
            books.push({ id, kode, judul, pengarang, kategori });
            localStorage.setItem('books', JSON.stringify(books));
            renderBooks();
            $('#modalBukuTambah').modal('hide');
        });
    
        document.getElementById('formbukuedit').addEventListener('submit', (e) => {
            e.preventDefault();
            const id = parseInt(document.getElementById('edit_id_buku').value);
            const kode = document.getElementById('edit_kode_buku').value;
            const judul = document.getElementById('edit_judul').value;
            const pengarang = document.getElementById('edit_pengarang').value;
            const kategori = document.getElementById('edit_kategori').value;
            const index = books.findIndex(b => b.id === id);
            books[index] = { id, kode, judul, pengarang, kategori };
            localStorage.setItem('books', JSON.stringify(books));
            renderBooks();
            $('#modalBukuEdit').modal('hide');
        });
    
        const deleteBook = (id) => {
            if (confirm('Yakin mau dihapus?')) {
                books = books.filter(b => b.id !== id);
                localStorage.setItem('books', JSON.stringify(books));
                renderBooks();
            }
        };
    
        document.addEventListener('DOMContentLoaded', renderBooks);
    </script>
    
    <p>
        <h4>Perpustakaan Menurut UU NO 43. THN. 2007</h4>
        Perpustakaan adalah institusi yang mengumpulkan pengetahuan tercetak dan terekam, mengelolanya dengan cara khusus guna
        memenuhi kebutuhan intelektualitas para penggunanya melalui berbagai cara interaksi pengetahuan.
        </p> 
@endsection
