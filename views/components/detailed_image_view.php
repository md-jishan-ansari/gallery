<?php


    $user_email = $_SESSION['email'];
    $id = $_GET['id'];

    // $query = "SELECT * FROM images_table where user_email = '$user_email' && id <= '$id' order by id desc limit 4";

    $query_one = "
        (SELECT * FROM images_table
        WHERE user_email = $1 AND id > $2
        AND id NOT IN (SELECT image_id FROM deleted_images)
        ORDER BY id
        LIMIT 4)";

    $query_second = "
        (SELECT * FROM images_table
        WHERE user_email = $1 AND id <= $2
        AND id NOT IN (SELECT image_id FROM deleted_images)
        ORDER BY id DESC
        LIMIT 4)";

    $query = "
        SELECT * FROM (
            $query_one
            UNION
            $query_second
        ) AS combined
        ORDER BY id DESC
    ";

    $query_run = pg_query_params($conn, $query, array($user_email, $id));


?>


<!-- <div id="imageStatus">

</div> -->

<div id="imageCarousel" class="carousel carousel-dark slide">

    <div class="carousel-inner" id="carousel_container">

        <?php
            if (pg_num_rows($query_run) > 0) {
                while ($row = pg_fetch_assoc($query_run)) {
                $img = $row['path'] . $row['image_name'] .  "." . $row['image_ext'];
                $img = "../../" . $img;
            ?>

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

