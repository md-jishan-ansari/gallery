let root_route = "../../";


$(document).ready(function() {
  $(".navbar-toggler").click(function(){
    if($(this).hasClass("collapsed")) {
      // $(".home_default_card").css("top", "45%")
      $(".home_default").css("top", "80px")
    } else {
      // $(".home_default_card").css("top", "60%")
      $(".home_default").css("top", "275px")
    }
  });
});

let bin_home_default = `
          <div class="home_default">
              <div class="card border-warning shadow-lg home_default_card">
                  <div class="card-body">
                      <h5 class="card-title">There are no any Deleted Images</h5>
                  </div>
              </div>
          </div>
        `;

let display_image_default = `
          <div class="home_default">

          <div class="card border-warning shadow-lg home_default_card">

              <div class="card-body">
                  <h5 class="card-title">There are no any uploaded Images</h5>
                  <p class="card-text">Please Upload your Images</p>
                  <button class="btn btn-success uploadBtn" data-bs-target="#imageUploadModal"
                      data-bs-toggle="modal" style="position: static;">
                      <i class="bi bi-upload"></i> Upload Images
                  </button>
              </div>
          </div>

          </div>
          `;

// ***************** User Login logout system handle here ***********
// ***************** User Login logout system handle here ***********
// ***************** User Login logout system handle here ***********

$(document).ready(function () {
  $.ajax({
    type: "POST",
    url: `${root_route}controllers/auth.php`,
    data: {
      get_session_during_reload: "get_session_during_reload",
    },
  }).done(function (data) {
    let obj = $.parseJSON(data);
    if (
      !obj.email &&
      !obj["404"] &&
      obj.current_url != "/views/pages/home.php" &&
      obj.current_url != "/views/pages/bin.php" &&
      obj.current_url != "/views/pages/"
    ) {
      $("#loginModalToggle").modal("show");

      $("#loginModalToggle").on("hidden.bs.modal", function () {
        if (!obj.email && !$("#signupModalToggle").is(":visible")) {
          $("#loginModalToggle").modal("show");
        }
      });

      $("#signupModalToggle").on("hidden.bs.modal", function () {
        if (!obj.email) {
          $("#loginModalToggle").modal("show");
        }
      });
    }
  });
});

// SIgnup handler
$("#signupForm").submit(function (e) {
  e.preventDefault();

  let formData = {
    signup: "signup",
    signup_username: e.target.signup_username.value,
    signup_email: e.target.signup_email.value,
    signup_password: e.target.signup_password.value,
    signup_cpassword: e.target.signup_cpassword.value,
  };

  console.log(formData);

  $.ajax({
    type: "POST",
    url: `${root_route}controllers/auth.php`,
    data: formData,
  }).done(function (data) {
    let obj = $.parseJSON(data);
    console.log(obj);
    if (obj.status === "success") {
      window.location.href = obj["url"];
    } else if (obj.status === "failed") {
      let status_str = `
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Hey! </strong> ${obj["message"]}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      `;

      $(".signupWarningContainer").html(status_str);
    }
  });
});

// login handler
$("#loginForm").submit(function (e) {
  e.preventDefault();

  let formData = {
    login: "login",
    login_email: e.target.login_email.value,
    login_password: e.target.login_password.value,
  };

  // console.log(formData);

  $.ajax({
    type: "POST",
    url: `${root_route}controllers/auth.php`,
    data: formData,
  }).done(function (data) {
    let obj = $.parseJSON(data);
    console.log(obj);
    if (obj.status === "success") {
      window.location.href = obj["url"];
    } else if (obj.status === "failed") {
      let status_str = `
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Hey! </strong> ${obj["message"]}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      `;
      $(".loginWarningContainer").html(status_str);
    }
  });
});

// ****************** Images Handled Here From Now ***************
// ****************** Images Handled Here From Now ***************
// ****************** Images Handled Here From Now ***************

// ********GET All Images
// ********GET All Images

function get_image_template(image, HTTP_HOST) {
  let img =
    root_route +
    image["path"] +
    image["image_name"]  +
    "." +
    image["image_ext"];

  // $encrypted_id = openssl_encrypt($image['id'], $ciphering, $secret_key, $options, $secret_iv);

  let str = `
    <div class="col col-lg-3 col-md-4 col-sm-6 col-12 mt-4" id="${
      image["id"]
    }" data-value="${image["id"]}">

      <div class="card shadow-sm" style="height: 100%;">
          <img src="${img}" class="card-img-top" alt="${image["image_name"]}">

          <div class="card-footer text-muted" style="height: 100%;">

              <a href="detailed_image.php?id=${image["id"]}">
                  <div class="card-image-overlay" onmouseover="imageMouseOver(${
                    image["id"]
                  })"
                      onmouseout="imageMouseOut(${image["id"]})">
                  </div>
              </a>

              <div class="card-btn-container" onmouseover="btnMouseOver(${
                image["id"]
              })"
                  onmouseout="btnMouseOut(${image["id"]})">

                  <!-- edit button -->
                  <button class="btn" onclick="show_Image_title_input(${
                    image["id"]
                  });"> 
                      <i class="bi bi-pencil-square"></i>
                  </button>

                  <!-- delete button -->
                  <button class="btn" onclick="delete_image(${
                    image["id"]
                  });">        
                      <i class="bi bi-trash3-fill"></i>
                  </button>

                  <!-- share button -->
                  <button class="btn" data-bs-target="#shareModalToggle-${
                    image["id"]
                  }"  
                      data-bs-toggle="modal">                                                        
                      <i class="bi bi-share-fill"></i>
                  </button>
              </div>

              <div class="modal fade" id="shareModalToggle-${
                image["id"]
              }" aria-hidden="true"
                  aria-labelledby="shareModalToggleLabel-${
                    image["id"]
                  }" tabindex="-1">
                  <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h1 class="modal-title fs-5" id="shareModalToggleLabel-${
                                image["id"]
                              }">
                                  Copy Image Link!</h1>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"
                                  aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                              <div class="input-group mb-3">
                                  <p>
                                  http://${HTTP_HOST}/views/pages/shared_image.php?img_id=${image['encripted_id']}
                                  </p>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>

              <p class="image_title">${
                image["image_name"] ? image["image_name"] : "unknown"
              }</p>

              <form class="image_title_input">
                  <div class="input-group">
                      <input type="hidden" name="image_id" value="${
                        image["id"]
                      }" />
                      <input type="text" aria-label="Image name" class="form-control" name="image_name" placeholder="untitled"
                          value="${image["image_name"]}">
                      <button class="btn btn-outline-secondary" type="submit" style="display: none;"></button>
                  </div>
              </form>
          </div>
      </div>
  </div>
  `;

  return str;
}

function get_display_images() {
  let lastImages = $(".image_row > *:last-child");
      let image_id = lastImages.data("value");

      $.ajax({
        type: "POST",
        url: `${root_route}controllers/image_handler.php`,
        data: {
          get_images: "get_images",
          image_id: image_id,
        },
      }).done(function (data) {
        let obj = $.parseJSON(data);

        console.log(obj);

        if (obj.status == "success") {
          let images = obj["images"];

          images.sort(function (a, b) {
            return -(a.id - b.id);
          });

          $.each(images, function (index, image) {

            let str = get_image_template(image, obj.HTTP_HOST);

            $(".image_row").append(str); //append child to carousel
          });
        } else if (obj.status == "zeroImages") {
          console.log("zero Images");
        }
      });
}






$(document).ready(function () {

  let scrollTimeout;

  // Function to be executed when scrolling ends
  function handleScroll() {
    // let totalHeight = document.body.clientHeight;
    // let currentHeight = window.innerHeight + window.pageYOffset;

    let display_images_container = $("#display_images_container");

    let totalHeight = display_images_container[0].scrollHeight;
    let currentHeight = display_images_container.scrollTop();

    if (totalHeight < currentHeight + 1000) {
      get_display_images();
    }
  }

  // Function to debounce scroll events
  function debounceScroll() {
    console.log("scroll");

    if (scrollTimeout) {
      clearTimeout(scrollTimeout);
    }

    scrollTimeout = setTimeout(handleScroll, 100); // Adjust the time interval as needed (in milliseconds)
  }

  $("#display_images_container").scroll(debounceScroll);
});

// ******************GET BIN IMAGES
// ******************GET BIN IMAGES


function get_bin_image_template(image) {
  let img =
    root_route +
    image["path"] +
    image["image_name"]  +
    "." +
    image["image_ext"];

  let str = `
  
  <div 
      class="col col-lg-3 col-md-4 col-sm-6 col-12 mt-4" 
      id="${image["id"]}" 
      data-value="${image["id"]}"
  >

    <div class="card shadow-sm" style="height: 100%;">
            <img 
                src="${img}" 
                class="card-img-top" 
                alt="${image["image_name"] }"
            >

            <div class="card-footer text-muted" style="height: 100%;">

                <div 
                    class="card-image-overlay" 
                    onmouseover="imageMouseOver(${ image["id"]})"
                    onmouseout="imageMouseOut(${image["id"]})">
                </div>

                <!-- select button -->
                <button 
                    class="btn btn-light selection_btn"
                    onclick="select_image_handler(${image["id"] });"
                >
                    <input class="form-check-input" type="checkbox" value="" disabled>
                </button>


                <div 
                    class="card-btn-container" 
                    onmouseover="btnMouseOver(${image["id"] })"
                    onmouseout="btnMouseOut(${image["id"]})"
                >
                    <!-- edit button -->
                    <button 
                        class="btn btn-light restoreBtn"
                        onclick="restoreImageFun(${image["id"]});"
                    >
                      <i class="bi bi-arrow-counterclockwise"></i> Restore
                    </button>

                    <!-- delete button -->
                    <form class="bin_image_delete_form" style="display: inline-block;">
                        <input 
                            type="hidden" 
                            value="${image["id"]}" 
                            name="image_id" 
                        />
                        <button 
                            class="btn" 
                            type="submit" 
                            name="delete_image" 
                            value="Delete"
                        >
                            <i class="bi bi-trash3-fill"></i>
                        </button>
                    </form>
                </div>

                <!-- Image Title start -->

                <p class="image_title">
                    ${image["image_name"] ? image["image_name"]: "unknown"}
                </p>

                <!-- Image Title End -->
          </div>
     </div>
    </div>
  
  `;

  return str;
}

function get_bin_images() {
  let lastImages = $(".image_row > *:last-child");
  let image_id = lastImages.data("value");

  $.ajax({
    type: "POST",
    url: `${root_route}controllers/image_handler.php`,
    data: {
      get_bin_images: "get_images",
      image_id: image_id,
    },
  }).done(function (data) {
    let obj = $.parseJSON(data);

    console.log(obj);

    if (obj.status == "success") {
      let images = obj["images"];

      images.sort(function (a, b) {
        return -(a.id - b.id);
      });

      $.each(images, function (index, image) {
        let str = get_bin_image_template(image, obj);

        // console.log(str);

        $("#bin_images_container .image_row").append(str); //append child to carousel
      });
    } else if (obj.status == "zeroImages") {
      console.log("zero Images");
    }
  });
}



$(document).ready(function () {

  let scrollTimeout;

  // let scrollTimeout;

  // Function to be executed when scrolling ends
  function handleScroll() {
    // let totalHeight = document.body.clientHeight;
    // let currentHeight = window.innerHeight + window.pageYOffset;

    let display_images_container = $("#bin_images_container");

    let totalHeight = display_images_container[0].scrollHeight;
    let currentHeight = display_images_container.scrollTop();

    if (totalHeight < currentHeight + 1000) {
      get_bin_images();
    }
  }

  // Function to debounce scroll events
  function debounceScroll() {
    console.log("scroll bin");

    if (scrollTimeout) {
      clearTimeout(scrollTimeout);
    }

    scrollTimeout = setTimeout(handleScroll, 100); // Adjust the time interval as needed (in milliseconds)
  }

  $("#bin_images_container").scroll(debounceScroll);
});

function imageMouseOver(id) {
  let cardimgOverlay = $(`#${id} .card-image-overlay`);
  let cardBtnContainer = $(`#${id} .card-btn-container`);

  cardimgOverlay.css("background", "rgba(60, 60, 60, 0.4)");
  cardBtnContainer.css("display", "block");
}

function imageMouseOut(id) {
  let cardimgOverlay = $(`#${id} .card-image-overlay`);
  let cardBtnContainer = $(`#${id} .card-btn-container`);

  cardBtnContainer.css("display", "none");
  cardimgOverlay.css("background", "unset");
}

function btnMouseOver(id) {
  let cardimgOverlay = $(`#${id} .card-image-overlay`);
  let cardBtnContainer = $(`#${id} .card-btn-container`);

  cardBtnContainer.css("display", "block");
  cardimgOverlay.css("background", "rgba(60, 60, 60, 0.4)");
}

function btnMouseOut(id) {
  let cardimgOverlay = $(`#${id} .card-image-overlay`);
  let cardBtnContainer = $(`#${id} .card-btn-container`);

  cardBtnContainer.css("display", "none");
  cardimgOverlay.css("background", "unset");
}

function show_Image_title_input(id) {
  let target_form = $("#" + id + " .image_title_input");
  let target_p = $("#" + id + " .image_title");
  let input_field = $("#" + id + " .image_title_input input[name=image_name]");

  $(".image_title_input").removeClass("editing");
  $(".image_title").removeClass("editing");

  target_form.addClass("editing");
  target_p.addClass("editing");

  input_field.focus();

  let inputValue = input_field.val();
  input_field.val("");
  input_field.val(inputValue);

  let inputElement = input_field.get(0);
  inputElement.scrollLeft = inputElement.scrollWidth;
}

// $(".image_title_input").submit(function (e) { this is not working for dynamically added form
$(document).on("submit", ".image_title_input", function (e) {
  //now it's working dynamic added form too

  e.preventDefault();

  let id = e.target.image_id.value;
  let image_name = e.target.image_name.value;

  let formData = {
    update_image: "update_image",
    id: id,
    image_name: image_name,
  };

  // console.log(formData);

  $.ajax({
    type: "POST",
    url: `${root_route}controllers/image_handler.php`,
    data: formData,
  }).done(function (data) {
    let obj = $.parseJSON(data);
    console.log(obj);
    if (obj.status === "success") {
      let target_p = $("#" + id + " .image_title");
      target_p.text(obj.image_name ? obj.image_name : "unknown");

      console.log(obj.image_name);
      $(".image_title_input").removeClass("editing");
      $(".image_title").removeClass("editing");
    }
  });
});

function delete_image(id) {
  // console.log("deleted");

  if (confirm("Are you sure you want to delete the Image")) {
    $.ajax({
      type: "POST",
      url: `${root_route}controllers/image_handler.php`,
      data: {
        delete_image: "delete_image",
        id: [id],
      },
    }).done(function (data) {
      let obj = $.parseJSON(data);
      if (obj.status === "success") {

        // console.log(obj);

        // console.log(`#${obj.image_id}`);

        $.each(obj.image_ids, function( index, id ) {
          console.log(`#${id}`);
          $(`#${id}`).remove();
        });

        // $(`#${obj.image_id}`).remove();

        if ( $('.image_row').children().length === 0 ) {
          $('.image_row').append(display_image_default);
        }

        if ( $('.image_row').children().length <= 8 ) {
          get_display_images();
        }

      }
    });
  }
}

$(document).on("submit", ".bin_image_delete_form", function (e) {
  e.preventDefault();

  if (confirm("Are you sure you want to delete the Image")) {
    let id = e.target.image_id.value;

    $.ajax({
      type: "POST",
      url: `${root_route}controllers/image_handler.php`,
      data: {
        bin_image_delete_form: "bin_image_delete_form",
        id: [id],
      },
    }).done(function (data) {
      let obj = $.parseJSON(data);
      if (obj.status === "success") {

        $.each(obj.image_ids, function( index, id ) {
          console.log(`#${id}`);
          $(`#${id}`).remove();
        });

        if ( $('.image_row').children().length === 0 ) {
          $('.image_row').append(bin_home_default);
        } else if ( $('.image_row').children().length <= 8 ) {
          get_bin_images();
        }


        // console.log(obj);
      }
    });
  }
});

function restoreImageFun(id) {
  $.ajax({
    type: "POST",
    url: `${root_route}controllers/image_handler.php`,
    data: {
      restoreImage: "bin_image_delete_form",
      id: id,
    },
  }).done(function (data) {
    let obj = $.parseJSON(data);
    if (obj.status === "success") {
      // console.log(obj);
      $(`#${obj.image_id}`).remove();

      if ( $('.image_row').children().length === 0 ) {
        $('.image_row').append(bin_home_default);
      } else if ( $('.image_row').children().length <= 8 ) {
        get_bin_images();
      }

    }
  });
}

//************************ * carousel start here

$(document).ready(function () {
  $("#imageCarousel").carousel({
    interval: 1000,
    wrap: false,
  });

  let prevBtn = $(".carousel-control-prev");
  let nextBtn = $(".carousel-control-next");

  let carouselFirstChild = $(".carousel-inner .carousel-item:nth-child(1)");
  let carouselLastChild = $(".carousel-inner .carousel-item:last-child");

  if (carouselFirstChild.hasClass("active")) {
    prevBtn.hide();
  } else if (carouselLastChild.hasClass("active")) {
    nextBtn.hide();
  }

  function get_carousel_image_template(value) {
    let img =
      root_route +
      value["path"] +
      value["image_name"] +
      "." +
      value["image_ext"];

    let str = `
              <div class="carousel-item" data-value="${value["id"]}">
                  <div class="imageCarouselCard">
                      <img src="${img}" class="d-block w-100" alt="${value["image_name"]}">
                      <p >${value["image_name"]} </p>
                  </div>
              </div>
            `;

    return str;
  }

  prevBtn.click(function () {
    let carouselSecondFirstChild = $(
      ".carousel-inner .carousel-item:nth-child(2)"
    );
    let carouselFirstChild = $(".carousel-inner .carousel-item:nth-child(1)");

    if (carouselSecondFirstChild.hasClass("active")) {
      let id = carouselFirstChild.data("value");

      $.ajax({
        type: "POST",
        url: `${root_route}controllers/image_handler.php`,
        data: {
          prev_image: "next_image",
          id: id,
        },
      }).done(function (data) {
        let obj = $.parseJSON(data);
        console.log(obj);

        if (obj.status == "success") {
          let images = obj["images"];

          images.sort(function (a, b) {
            return a.id - b.id;
          });

          // $(".carousel-item").removeClass("active");

          let flag = true;

          $.each(images, function (index, value) {
            // console.log(index, value);

            let str = get_carousel_image_template(value);

            $("#carousel_container").prepend(str); //append child to carousel
          });
        } else if (obj.status == "zeroImages") {
          console.log("there is no image more");
        }
      });
    }
  });

  nextBtn.click(function () {
    let carouselSecondLastChild = $(
      ".carousel-inner .carousel-item:last-child"
    ).prev();
    let carouselLastChild = $(".carousel-inner .carousel-item:last-child");

    if (carouselSecondLastChild.hasClass("active")) {
      let id = carouselLastChild.data("value");

      $.ajax({
        type: "POST",
        url: `${root_route}controllers/image_handler.php`,
        data: {
          next_image: "next_image",
          id: id,
        },
      }).done(function (data) {
        let obj = $.parseJSON(data);

        console.log(obj);

        if (obj.status == "success") {
          let images = obj["images"];

          images.sort(function (a, b) {
            return -(a.id - b.id);
          });

          // $(".carousel-item").removeClass("active");

          let flag = true;
          $.each(images, function (index, value) {
            flag = false;

            let str = get_carousel_image_template(value);

            $("#carousel_container").append(str); //append child to carousel
          });
        } else if (obj.status == "zeroImages") {
          // $("#imageStatus").html(str);
          console.log("there is no image more");
        }
      });
    }
  });

  $("#imageCarousel").on("slid.bs.carousel", "", function () {
    prevBtn.show();
    nextBtn.show();

    let carouselFirstChild = $(".carousel-inner .carousel-item:nth-child(1)");
    let carouselLastChild = $(".carousel-inner .carousel-item:last-child");

    let currentChild = $(".carousel-item.active");

    $.ajax({
      type: "POST",
      url: `${root_route}controllers/image_handler.php`,
      data: {
        set_session_id_forurl: "set_session_id",
        image_id: currentChild.data("value"),
      },
    }).done(function (data) {
      console.log(data);
    });

    if (carouselFirstChild.hasClass("active")) {
      prevBtn.hide();
    } else if (carouselLastChild.hasClass("active")) {
      nextBtn.hide();
    } else {
      $("#imageStatus").html("");
    }
  });
});

//*********************** */ carousel End here

// ***********************Bin checked Images
let bin_checked_images = [];

function selectHandler() {
  let checkbox = $(`.form-check-input`);

  if($('#bin_images_container').hasClass("selectionStart")) {  // show check boxes
    $('#bin_images_container').removeClass("selectionStart");

    
  } else {                                                     // hide check boxes
    $('#bin_images_container').addClass("selectionStart");

    checkbox.prop("checked", false);
    bin_checked_images = [];
  }
}

function select_image_handler(id) {
  let checkbox = $(`#${id} .form-check-input`);

  checkbox.prop("checked", !checkbox.prop("checked"));

  let isChecked = checkbox.is(":checked");

  if (isChecked) {
    bin_checked_images.push(id);
  } else if (bin_checked_images.indexOf(id) !== -1) {
    bin_checked_images.splice(bin_checked_images.indexOf(id), 1);
  }

  console.log(bin_checked_images);
}



// function restoreImageFun(id) {
//   $.ajax({
//     type: "POST",
//     url: `${root_route}controllers/image_handler.php`,
//     data: {
//       restoreImage: "bin_image_delete_form",
//       id: id,
//     },
//   }).done(function (data) {
//     let obj = $.parseJSON(data);
//     if (obj.status === "success") {
//       // console.log(obj);
//       $(`#${obj.image_id}`).remove();

//       if ( $('.image_row').children().length === 0 ) {
//         $('.image_row').append(bin_home_default);
//       } else if ( $('.image_row').children().length <= 8 ) {
//         get_bin_images();
//       }

//     }
//   });
// }




function RestoreSelected() {
    $.ajax({
      type: "POST",
      url: `${root_route}controllers/image_handler.php`,
      data: {
        RestoreSelected: "deleteSelected",
        id: bin_checked_images,
      },
    }).done(function (data) {
      let obj = $.parseJSON(data);
      if (obj.status === "success") {
  
        $.each(obj.image_ids, function( index, id ) {
          console.log(`#${id}`);
          $(`#${id}`).remove();
        });

        if ( $('.image_row').children().length === 0 ) {
          $('.image_row').append(bin_home_default);
        } else if ( $('.image_row').children().length <= 8 ) {
          get_bin_images();
        }
  
        // console.log(obj);
      }
    });
  
}

function RestoreAllBin() {
  $.ajax({
      type: "POST",
      url: `${root_route}controllers/image_handler.php`,
      data: {
        RestoreAllBin: "deleteAllBin",
        id: bin_checked_images,
        all: true
      },
    }).done(function (data) {
      let obj = $.parseJSON(data);
      if (obj.status === "success") {
  
        // console.log(obj);
  
        $.each(obj.image_ids, function( index, id ) {
          console.log(`#${id}`);
          $(`#${id}`).remove();
        });
  
        if ( $('.image_row').children().length === 0 ) {
          $('.image_row').append(bin_home_default);
        }
  
      }
    });
  
}

function deleteSelected() {
  if (confirm("Are you sure you want to delete these selected Images")) {
    $.ajax({
      type: "POST",
      url: `${root_route}controllers/image_handler.php`,
      data: {
        deleteSelected: "deleteSelected",
        id: bin_checked_images,
      },
    }).done(function (data) {
      let obj = $.parseJSON(data);
      if (obj.status === "success") {
  
        $.each(obj.image_ids, function( index, id ) {
          console.log(`#${id}`);
          $(`#${id}`).remove();
        });
  
        if ( $('.image_row').children().length === 0 ) {
          $('.image_row').append(bin_home_default);
        }

        else if ( $('.image_row').children().length <= 8 ) {
          get_bin_images();
        }
  
        // console.log(obj);
      }
    });
  }
  
}

function deleteAllBin() {
  if (confirm("Are you sure you want to delete all these Images")) {
    $.ajax({
      type: "POST",
      url: `${root_route}controllers/image_handler.php`,
      data: {
        deleteAllBin: "deleteAllBin",
        id: bin_checked_images,
        all: true
      },
    }).done(function (data) {
      let obj = $.parseJSON(data);
      if (obj.status === "success") {
  
        // console.log(obj);
  
        $.each(obj.image_ids, function( index, id ) {
          console.log(`#${id}`);
          $(`#${id}`).remove();
        });
  
        if ( $('.image_row').children().length === 0 ) {
          $('.image_row').append(bin_home_default);
        }
  
      }
    });
  }
  
}


// ***********************gallery checked Images

let gallery_checked_images = [];

function imageSelectHandler() {
  let checkbox = $(`.form-check-input`);

  if($('#display_images_container').hasClass("selectionStart")) {  // show check boxes
    $('#display_images_container').removeClass("selectionStart");

    
  } else {                                                     // hide check boxes
    $('#display_images_container').addClass("selectionStart");

    checkbox.prop("checked", false);
    gallery_checked_images = [];
  }
}

function select_gallery_image_handler(id) {
  let checkbox = $(`#${id} .form-check-input`);

  checkbox.prop("checked", !checkbox.prop("checked"));

  let isChecked = checkbox.is(":checked");

  if (isChecked) {
    gallery_checked_images.push(id);
  } else if (gallery_checked_images.indexOf(id) !== -1) {
    gallery_checked_images.splice(gallery_checked_images.indexOf(id), 1);
  }

  console.log(gallery_checked_images);
}

function deleteSelectedImage() {
  if (confirm("Are you sure you want to delete these selected Images")) {
    $.ajax({
      type: "POST",
      url: `${root_route}controllers/image_handler.php`,
      data: {
        deleteSelectedImage: "deleteSelectedImage",
        id: gallery_checked_images,
      },
    }).done(function (data) {
      let obj = $.parseJSON(data);
      if (obj.status === "success") {
  
        $.each(obj.image_ids, function( index, id ) {
          console.log(`#${id}`);
          $(`#${id}`).remove();
        });

        // $(`#${obj.image_id}`).remove();

        if ( $('.image_row').children().length === 0 ) {
          $('.image_row').append(display_image_default);
        }

        if ( $('.image_row').children().length <= 8 ) {
          get_display_images();
        }
  
        // console.log(obj);
      }
    });
  }
  
}

function deleteAllImages() {
  if (confirm("Are you sure you want to delete all these Images")) {
    $.ajax({
      type: "POST",
      url: `${root_route}controllers/image_handler.php`,
      data: {
        deleteAllImages: "deleteAllImages",
        id: gallery_checked_images,
        all: true
      },
    }).done(function (data) {
      let obj = $.parseJSON(data);
      if (obj.status === "success") {
  
        $.each(obj.image_ids, function( index, id ) {
          console.log(`#${id}`);
          $(`#${id}`).remove();
        });

        // $(`#${obj.image_id}`).remove();

        if ( $('.image_row').children().length === 0 ) {
          $('.image_row').append(display_image_default);
        }

        if ( $('.image_row').children().length <= 8 ) {
          get_display_images();
        }
  
      }
    });
  }
  
}

