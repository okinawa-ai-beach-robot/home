const swiper = new Swiper('.swiper', {
    loop: true,
    autoplay: {
        delay: 3000,
        disableOnInteraction: false
    },
    effect: 'fade',
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev'
    },
    pagination: {
        el: '.swiper-pagination',
        clickable: true
    }
});

Dropzone.autoDiscover = false; // Disable Dropzone's automatic initialization
const myDropzone = new Dropzone("#my-dropzone", {
    url: "upload.php",
    paramName: "file[]", // Name of the file input
    maxFilesize: 2, // MB
    acceptedFiles: "image/*",
    addRemoveLinks: true,
    dictDefaultMessage: "Drag & drop images here or click to upload",
    success: function(file, response) {
        alert("File uploaded successfully: " + file.name);
    },
    error: function(file, errorMessage) {
        alert("Error uploading file: " + errorMessage);
    }
});
