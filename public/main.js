import { Dropzone } from "dropzone";

// Initialize Dropzone
const dropzone = new Dropzone("div#imageUpload", {
  url: "/file/post", // specify the URL to send the files to
});

