<?php

$user_email = $_SESSION['email'];

$query = "SELECT * FROM images_table WHERE user_email = $1 AND id IN (SELECT image_id FROM deleted_images) ORDER BY id DESC LIMIT 12";
$query_run = pg_query_params($conn, $query, array($user_email));

?>

<div style="width: 100vw; height: calc(100vh - 82px); overflow-Y: scroll;" id="bin_images_container" class="selectionStart" >
    <div class="container">
        <div class="binContainerBtns">
            <button class="btn btn-success navBtn temp" onclick="selectHandler();">Select</button>
            <button class="btn btn-primary navBtn" onclick="RestoreSelected();">Restore Selected</button>
            <button class="btn btn-primary navBtn" onclick="RestoreAllBin();">Restore All</button>
            <button class="btn btn-danger navBtn" onclick="deleteSelected();">Delete Selected</button>
            <button class="btn btn-danger navBtn" onclick="deleteAllBin();">Delete All</button>
        </div>

        <div class="row image_row">
            <?php
            if (pg_num_rows($query_run) > 0) {
                while ($row = pg_fetch_assoc($query_run)) {
                    $img = $row['path'] . $row['image_name'] .  "." . $row['image_ext'];
                    $img = "../../" . $img;
                    ?>
                    <div class="col col-lg-3 col-md-4 col-sm-6 col-12 mt-4" id="<?php echo $row['id']; ?>" data-value="<?php echo $row['id']; ?>">
                        <div class="card shadow-sm" style="height: 100%;">
                            <img src="<?php echo $img ?>" class="card-img-top" alt="...">
                            <div class="card-footer text-muted" style="height: 100%;">
                                <div class="card-image-overlay" onmouseover="imageMouseOver(<?php echo $row['id']; ?>)"
                                    onmouseout="imageMouseOut(<?php echo $row['id']; ?>)">
                                </div>

                                <!-- select button -->
                                <button class="btn btn-light selection_btn"
                                    onclick="select_image_handler(<?php echo $row['id']; ?>);">
                                    <input class="form-check-input" type="checkbox" value="" disabled>
                                </button>

                                <div class="card-btn-container" onmouseover="btnMouseOver(<?php echo $row['id']; ?>)"
                                    onmouseout="btnMouseOut(<?php echo $row['id']; ?>)">
                                    <!-- restore button -->
                                    <button class="btn btn-light restoreBtn"
                                        onclick="restoreImageFun(<?php echo $row['id']; ?>);">
                                        <i class="bi bi-arrow-counterclockwise"></i> Restore
                                    </button>

                                    <!-- delete button -->
                                    <form class="bin_image_delete_form" style="display: inline-block;">
                                        <input type="hidden" value="<?php echo $row['id']; ?>" name="image_id" />
                                        <button class="btn" type="submit" name="delete_image" value="Delete">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Image Title start -->
                                <p class="image_title">
                                    <?php if($row['image_name']) echo $row['image_name']; else echo "unknown";  ?>
                                </p>
                                <!-- Image Title End -->
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="home_default">
                    <div class="card border-warning shadow-lg home_default_card">
                        <div class="card-body">
                            <h5 class="card-title">There are no any Deleted Images</h5>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>