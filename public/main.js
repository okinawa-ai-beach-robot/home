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

Dropzone.autoDiscover = false;  // Prevent auto-instantiation

const myDropzone = new Dropzone("#uploadForm", {
    paramName: "file[]",
    maxFilesize: 5, // 5 MB limit per file
    acceptedFiles: "image/*",
    addRemoveLinks: true,
    dictDefaultMessage: "Drag and drop images here or click to upload.",
    init: function() {
        this.on("success", function(file, response) {
            console.log("Upload successful:", response);
        });
        this.on("error", function(file, errorMessage) {
            console.error("Upload failed:", errorMessage);
        });
    }
});
