<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container">
    <h1>Debug Linear Position</h1>
    
    <div class="card mb-4">
        <div class="card-header">
            <h5>Informasi Posisi Linear Pengguna</h5>
        </div>
        <div class="card-body">
            <p>Halaman ini menampilkan status posisi linear semua pengguna dalam sistem. Posisi linear adalah angka 1-12 yang digunakan untuk menghitung kecocokan.</p>
            <p>Setiap perbedaan 1 tingkat posisi linear = pengurangan kecocokan sebesar 8.33%</p>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h5>Daftar Pengguna</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Gender</th>
                        <th>Kasta</th>
                        <th>Posisi Linear</th>
                        <th>Nama Posisi</th>
                        <th>Confidence Score</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user->user_id ?></td>
                        <td><?= $user->username ?></td>
                        <td><?= $user->gender ?></td>
                        <td><?= $user->kasta_name ?> (<?= $user->kasta_id ?>)</td>
                        <td><?= $user->linear_position ?: 'NULL' ?></td>
                        <td><?= $user->linear_position_name ?></td>
                        <td><?= $user->confidence_score ? round($user->confidence_score) . '%' : 'N/A' ?></td>
                        <td>
                            <a href="<?= base_url('matches/recalculate/' . $user->user_id) ?>" class="btn btn-sm btn-primary">Recalculate</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-4">
        <a href="<?= base_url('migration') ?>" class="btn btn-warning">Run Migrations</a>
        <a href="<?= base_url('migration/add_updated_at') ?>" class="btn btn-info">Add updated_at Column</a>
        <a href="<?= base_url('matches') ?>" class="btn btn-secondary">Back to Matches</a>
    </div>
</div> 