<?php


    $user_email = $_SESSION['email'];
    $id = $_GET['id'];

    // $query = "SELECT * FROM images_table where user_email = '$user_email' && id <= '$id' order by id desc limit 4";

    $query_one = "(SELECT * FROM images_table where user_email = '$user_email' && id > '$id' && id not in (SELECT image_id from deleted_images) order by id limit 1)";
    $query_second = "(SELECT * FROM images_table where user_email = '$user_email' && id <= '$id' && id not in (SELECT image_id from deleted_images) order by id desc limit 2)";
    $query = $query_one . " UNION " . $query_second;

    $query_run = mysqli_query($conn, $query);

?> 


<!-- <div id="imageStatus">
    
</div> -->

<div id="imageCarousel" class="carousel carousel-dark slide">

    <div class="carousel-inner" id="carousel_container">

        <?php
            if (mysqli_num_rows($query_run) > 0) {
                foreach ($query_run as $row) {
                $img = $row['path'] . $row['image_name'] .  "." . $row['image_ext'];
                $img = "../../" . $img;
            ?>

        <?php print_r($_SESSION['temp_current_queries']); ?>

            <div class="carousel-item <?php if($row['id'] == $id) echo 'active' ?>" data-value="<?php echo $row['id']; ?>">

                <div class="imageCarouselCard">
                    <img src="<?php echo $img ?>" alt="...">
                    <p >
                        <?php echo $row['image_name']; ?>
                    </p>
                </div> 
            </div>

        <?php }} ?>

    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev" style="background-color: gray:">
        <div>
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </div>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next">
        <div>
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </div>
    </button>
</div>

