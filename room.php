<?php
include 'db_connect.php';
?>
<div class="container-fluid">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <!-- Add Room card -->
                    <div class="col-sm-6">
                        <div class="card border-primary mt-3">
                            <div class="card-body bg-light">
                                <h4><b><i class="fa fa-plus-circle"></i> Add Room</b></h4> <!-- Added Font Awesome icon -->
                            </div>
                            <div class="card-footer">
                                <div class="col-md-12">
                                    <a href="index.php?page=add_room" class="d-flex justify-content-between">
                                        <span>Add</span>
                                        <span class="fa fa-chevron-circle-right"></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- View Room card -->
                    <div class="col-sm-6">
                        <div class="card border-primary mt-3">
                            <div class="card-body bg-light">
                                <h4><b><i class="fas fa-bed"></i> View Room</b></h4> <!-- Added Font Awesome icon -->
                            </div>
                            <div class="card-footer">
                                <div class="col-md-12">
                                    <a href="index.php?page=view_room" class="d-flex justify-content-between">
                                        <span>View</span>
                                        <span class="fa fa-chevron-circle-right"></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Add more cards here with the same style and class attributes -->
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .container-fluid {
        margin-top: 30px;
    }
</style>
