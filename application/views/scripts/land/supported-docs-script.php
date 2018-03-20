<script>
    Dropzone.autoDiscover = false;
    //configuration
    Dropzone.options.verifyDocs = {
        maxFilesize: 4,
        uploadMultiple: false,
        addRemoveLinks: true,
        maxFiles: 5,
        init: function () {
            this.on("addedfile", function (file) {
                // Create the remove button
//                var removeButton = Dropzone.createElement("<button class='btn btn-danger btn-sm' class='remove-image-btn'>Remove file</button>");
                // Capture the Dropzone instance as closure.
//                var _this = this;
                // Listen to the click event
//                removeButton.addEventListener("click", function (e) {
//                    // Make sure the button click doesn't submit the form:
//                    e.preventDefault();
//                    e.stopPropagation();
//                    // Remove the file preview.
//                    _this.removeFile(file);
//                    // If you want to the delete the file on the server as well,
//                    // you can do the AJAX request here.
//                });

                // Add the button to the file preview element.
//                file.previewElement.appendChild(removeButton);
            });
        },
        accept: function (file, done) {
            if (file.type !== "application/pdf" && file.type !== 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
                done("Error! Files of this type are not accepted");
            }
            else {
                done();
            }
        },
        success: function (file, response) {
            console.log(file);
            var filename = Dropzone.createElement("<input type='hidden' name='docs[]' id='docs' value='" + response.errors.file_name + "'>");
            file.previewElement.appendChild(filename);
        }
    };
    var myDropzone2 = new Dropzone("div#verify-docs", {url: "<?php echo $docs_upload_url; ?>"});
    $(".dz-message").html('<i class="fa fa-cloud"></i><br><span class="h6">Upload File</span>');
</script>