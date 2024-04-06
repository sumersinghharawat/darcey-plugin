<?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $styles = isset($_POST['styles']) ? $_POST['styles'] : array();
		
		
        update_option('nayagroup_styles', $styles);
    }
    
    ?>
    <style>
        div#styles-container {
            display: flex !important;
            flex-direction: column !important;
            gap: 36px;
        }

        .packages-container {
            display: flex !important;
            flex-wrap: wrap !important;
            gap: 2%;
            margin: 10px 0px !important;
        }

        .package {
            padding: 12px !important;
            border: 1px solid #000 !important;
            width: 30.2% !important;
        }

        .style {
            padding: 10px !important;
            border: 1px solid #000 !important;
        }
        
		#styles-container button, .submit-product-varaint button, .submit-product-varaint input {
			background: #000 !important;
			color: #fff !important;
			border: none !important;
            text-align: center;
            width: 100% !important;
			padding: 12px !important;
            margin: 10px 0px !important;
		}
		.preview-image-container {
			padding: 10px 0px;
		}
		.package input {
			width: 100% !important;
			margin-top: 10px !important;
		}
        .variants-container {
            display: flex !important;
            flex-wrap: wrap !important;
            gap: 10px;
            margin: 10px 0px;
        }
        .package-variant {
            width: 46% !important;
            display: block !important;
            padding: 5px;
            border: 1px solid #000;
        }

        .package-variant img {
            width: 100% !important;
        }
        .submit-product-varaint {
            display: flex !important;
            padding: 20px 0px !important;
            gap: 20px !important;
        }
        #styles-container .add-package, #styles-container .remove-style {
            width: 180px !important;
        }
        .submit-product-varaint button, .submit-product-varaint input {
            width: 220px !important;
        }
		input[type=date], input[type=datetime-local], input[type=datetime], input[type=email], input[type=month], input[type=number], input[type=password], input[type=search], input[type=tel], input[type=text], input[type=time], input[type=url], input[type=week] {
			border: 1px solid #000;
			border-radius: 0px;
			padding: 4.5px;
		}
		.product-update {
			display: flex;
			align-items: center;
			gap: 10px;
		}
    </style>
    <div class="wrap">
        <h1>Add New Style Package</h1>
        <form method="post" action="" enctype="multipart/form-data">
            <div id="styles-container">
                <?php

                $styles = get_option('nayagroup_styles');

                if (isset($styles)) {
                    ?>
                    <?php                    
                    foreach ($styles as $stylekey => $stylevalue) {
                    ?>

                        <div class="style" id="style-<?php echo $stylekey;?>">
                        <input type="text" name="styles[<?php echo $stylekey; ?>][name]" placeholder="Style Name" value="<?php echo $stylevalue['name']; ?>">
                        <button type="button" class="remove-style">Remove Style</button>
                        <div class="packages-container">

                        <?php
                        foreach ($styles[$stylekey]['packages'] as $packagekey => $packagevalue) {
                        ?>
                            <div class="package" id="package-<?php echo $packagekey; ?>">
                            
                                <div class="preview-image-container" data-package="<?php echo $stylekey; ?>-<?php echo $packagekey;?>">
                                    <?php $imgURL = wp_get_attachment_image_src($packagevalue['image'], 'thumbnail'); ?>
                                    <img src="<?php echo $imgURL[0];?>" alt="Package Image" style="height: 200px;width:300px;" />
                                </div>
                                <button type="button" class="upload-image-button" data-package="<?php echo $packagekey; ?>" data-style="<?php echo $stylekey?>">Upload Image</button>
                                <input type="text" name="styles[<?php echo $stylekey; ?>][packages][<?php echo $packagekey; ?>][name]" placeholder="Package Name" value="<?php echo $packagevalue['name']; ?>">
                                <input type="number" name="styles[<?php echo $stylekey; ?>][packages][<?php echo $packagekey; ?>][price]" placeholder="Price" value="<?php echo $packagevalue['price']; ?>">
                                <input type="number" name="styles[<?php echo $stylekey; ?>][packages][<?php echo $packagekey; ?>][initial_price]" placeholder="Initial Price"  value="<?php echo $packagevalue['initial_price'];?>">
                                <input type="hidden" name="styles[<?php echo $stylekey;?>][packages][<?php echo $packagekey;?>][image]"  class="package-image" data-package="<?php echo $stylekey;?>-<?php echo $packagekey;?>" value="<?php echo $packagevalue['image']; ?>" />
                                
                                <div class="variants-container"  data-variant="<?php echo $stylekey; ?>-<?php echo $packagekey; ?>">
                                    <?php
                                        foreach ($packagevalue['variant'] as $variantKey => $variantValue) {
                                    ?>
                                        <div class="package-variant">
                                            <div class="preview-image-container" data-package-variant="<?php echo $stylekey; ?>-<?php echo $packagekey; ?>-<?php echo $variantKey; ?>">
                                                <?php $imgURL = wp_get_attachment_image_src($variantValue['image'], 'thumbnail'); ?>
                                                <img src="<?php echo $imgURL[0]; ?>" alt="Package Image" style="height: 200px;width:300px;" />
                                            </div>
                                            <button type="button" class="upload-image-button" data-package="<?php echo $packagekey; ?>" data-style="<?php echo $stylekey; ?>" data-variant="<?php echo $variantKey; ?>">Upload Image</button>
                                            <input type="text" name="styles[<?php echo $stylekey; ?>][packages][<?php echo $packagekey; ?>][variant][<?php echo $variantKey;?>][name]" placeholder="Variant Name" value="<?php echo $variantValue['name'];?>"/>
                                            <input type="hidden" name="styles[<?php echo $stylekey; ?>][packages][<?php echo $packagekey; ?>][variant][<?php echo $variantKey; ?>][image]"  class="variant-image" data-package-variant="<?php echo $stylekey; ?>-<?php echo $packagekey; ?>-<?php echo $variantKey;?>" value="<?php echo $variantValue['image']; ?>" />
                                            <button type="button" class="remove-package-variant">Remove Variant</button>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <button type="button" class="add-package-variant" id="add-package-<?php echo $stylekey;?>-variant-<?php echo $packagekey; ?>">Add Variant</button>
                                <button type="button" class="remove-package">Remove Package</button>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                        <button type="button" class="add-package" id="add-package-<?php echo $stylekey;?>">Add Package</button>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <div class="submit-product-varaint ">
                <button type="button" id="add-style">Add Style</button>
                <input type="submit" class="button-primary" value="Save">
            </div>
        </form>
        <div>
            <p>
                One click for update your all subscription products!!!
            </p>
			<div class="product-update">
	            <input type="button" class="button-primary" id="updateproduct" value="Update Product"><span id="loader-gif"></span>
			</div>
        </div>

        <script>
            document.getElementById("add-style").addEventListener('click', function(event) {
                var stylesContainer = document.getElementById('styles-container');
                var styleCount = document.querySelectorAll(".style").length;

                var newStyle = document.createElement('div');
                newStyle.className = 'style';
                newStyle.id = 'style-' + styleCount;

                // Create input for Style Name
                var styleNameInput = document.createElement('input');
                styleNameInput.type = 'text';
                styleNameInput.name = 'styles[' + styleCount + '][name]';
                styleNameInput.placeholder = 'Style Name';
                newStyle.appendChild(styleNameInput);

                // Create button for removing the style
                var removeStyleButton = document.createElement('button');
                removeStyleButton.type = 'button';
                removeStyleButton.className = 'remove-style';
                removeStyleButton.innerText = 'Remove Style';
                removeStyleButton.addEventListener('click', function() {
                    newStyle.remove();
                });
                newStyle.appendChild(removeStyleButton);

                // Create packages container
                var packagesContainer = document.createElement('div');
                packagesContainer.className = 'packages-container';

                // Create button for adding a new package
                var addPackageButton = document.createElement('button');
                addPackageButton.type = 'button';
                addPackageButton.className = 'add-package';
                addPackageButton.id = 'add-package-' + styleCount;
                addPackageButton.innerText = 'Add Package';

                addPackageButton.addEventListener('click', function() {
                    var packageCount = packagesContainer.querySelectorAll(".package").length;
                    var newPackage = document.createElement('div');
                    newPackage.className = 'package';

                    // Create input for Package Name
                    var packageNameInput = document.createElement('input');
                    packageNameInput.type = 'text';
                    packageNameInput.name = 'styles[' + styleCount + '][packages][' + packageCount + '][name]';
                    packageNameInput.placeholder = 'Package Name';
                    newPackage.appendChild(packageNameInput);

                    // Create input for Price
                    var priceInput = document.createElement('input');
                    priceInput.type = 'number';
                    priceInput.name = 'styles[' + styleCount + '][packages][' + packageCount + '][price]';
                    priceInput.placeholder = 'Price';
                    newPackage.appendChild(priceInput);

                    // Create input for Initial Price
                    var initialPriceInput = document.createElement('input');
                    initialPriceInput.type = 'number';
                    initialPriceInput.name = 'styles[' + styleCount + '][packages][' + packageCount + '][initial_price]';
                    initialPriceInput.placeholder = 'Initial Price';
                    newPackage.appendChild(initialPriceInput);

                    // Create input for Image
                    var imageInput = document.createElement('input');
                    imageInput.type = 'file';
                    imageInput.name = 'styles[' + styleCount + '][packages][' + packageCount + '][image]';
                    imageInput.accept = 'image/*';
                    newPackage.appendChild(imageInput);

                    // Create button for removing the package
                    var removePackageButton = document.createElement('button');
                    removePackageButton.type = 'button';
                    removePackageButton.className = 'remove-package';
                    removePackageButton.innerText = 'Remove Package';
                    removePackageButton.addEventListener('click', function() {
                        newPackage.remove();
                    });
                    newPackage.appendChild(removePackageButton);


                    // Create button for adding a new package
                    var addVariantButton = document.createElement('button');
                    addVariantButton.type = 'button';
                    addVariantButton.className = 'add-variant';
                    addVariantButton.id = 'add-variant-' + styleCount;
                    addVariantButton.innerText = 'Add Variant';
                    newPackage.appendChild(addVariantButton);

                    var variantsContainer = document.createElement('div');
                    variantsContainer.className = 'variants-container';

                    addVariantButton.addEventListener('click', function() {
                        var variantsCount = variantsContainer.querySelectorAll(".variant").length;
                        var newVariant = document.createElement('div');
                        newVariant.className = 'variant';

                        // Create input for Package Name
                        var variantNameInput = document.createElement('input');
                        variantNameInput.type = 'text';
                        variantNameInput.name = 'styles[' + styleCount + '][package][' + variantCount + '][variant][name]';
                        variantNameInput.placeholder = 'Variant Name';
                        newVariant.appendChild(variantNameInput);

                        variantsContainer.appendChild(newVariant);

                    });


                    // Append the new package to the packages container
                    packagesContainer.appendChild(newPackage);

                    // Use the featured image URL in your JavaScript code
                    var featuredPackageImageURL = jQuery('.preview-image-container:first img').attr('src');
                    console.log('Featured Package Image URL:', featuredPackageImageURL);
                });

                // Append the packages container and add-package button to the new style
                newStyle.appendChild(packagesContainer);
                newStyle.appendChild(addPackageButton);

                // Append the new style to the styles container
                stylesContainer.appendChild(newStyle);
            });

            jQuery(".add-package").click((e) => {
                var id = jQuery(e.target).attr("id");
                var styleCount = id.split("-")[(id.split("-")).length - 1];

                id = jQuery(e.target).parent(".style").attr("id");
                styleCount = id.split("-")[(id.split("-")).length - 1];


                // var packagesContainer = document.getElementById(id);
                var element = document.getElementById(id);
                var packagesContainer = element.querySelector(".packages-container");

                var packageCount = packagesContainer.querySelectorAll(".package").length;
                var newPackage = document.createElement('div');
                newPackage.className = 'package';

                var previewImageContainer = document.createElement('div');
                previewImageContainer.className = 'preview-image-container';
                previewImageContainer.setAttribute('data-package', styleCount+'-'+packageCount);

                // Create the image element
                var imageElement = document.createElement('img');
                imageElement.src = ''; // Set the source of the image
                imageElement.alt = 'Package Image';
                imageElement.style.height = '200px'; // Set the height of the image
                imageElement.style.width = '300px'; // Set the width of the image

                // Append the image element to the div container
                previewImageContainer.appendChild(imageElement);

                // Assuming newPackage is defined somewhere
                newPackage.appendChild(previewImageContainer); // Append the div container to the parent element
                
                // Create the upload image button
                var uploadImageButton = document.createElement('button');
                uploadImageButton.type = 'button';
                uploadImageButton.className = 'upload-image-button';
                uploadImageButton.setAttribute('data-package', packageCount);
                uploadImageButton.setAttribute('data-style', styleCount);
                uploadImageButton.setAttribute('data-variant', null);
                uploadImageButton.innerText = 'Upload Image';
                newPackage.appendChild(uploadImageButton);
                
                jQuery(newPackage).on('click', '.upload-image-button', function(e) {
                    e.preventDefault();
                    var packageKey = jQuery(this).data('package');
                    var styleKey = jQuery(this).data('style');
                    console.log(packageKey);
                    var customUploader = wp.media({
                        title: 'Choose an Image',
                        library: {
                            type: 'image'
                        },
                        button: {
                            text: 'Choose Image'
                        },
                        multiple: false
                    });

                    customUploader.on('select', function() {
                        var attachment = customUploader.state().get('selection').first().toJSON();
                        console.log(attachment);
                        jQuery('.package-image[data-package="' + styleKey + "-" + packageKey + '"]').val(attachment.id);
                        jQuery('.preview-image-container[data-package="' + styleKey + "-" + packageKey + '"] img').attr('src', attachment.url);
                    });

                    customUploader.open();
                });

                // Create input for Package Name
                var packageNameInput = document.createElement('input');
                packageNameInput.type = 'text';
                packageNameInput.name = 'styles[' + styleCount + '][packages][' + packageCount + '][name]';
                packageNameInput.placeholder = 'Package Name';
                newPackage.appendChild(packageNameInput);

                // Create input for Price
                var priceInput = document.createElement('input');
                priceInput.type = 'number';
                priceInput.name = 'styles[' + styleCount + '][packages][' + packageCount + '][price]';
                priceInput.placeholder = 'Price';
                newPackage.appendChild(priceInput);

                // Create input for Initial Price
                var initialPriceInput = document.createElement('input');
                initialPriceInput.type = 'number';
                initialPriceInput.name = 'styles[' + styleCount + '][packages][' + packageCount + '][initial_price]';
                initialPriceInput.placeholder = 'Initial Price';
                newPackage.appendChild(initialPriceInput);
                
                // Create the hidden input element
                var hiddenInputElement = document.createElement('input');
                hiddenInputElement.type = 'hidden';
                hiddenInputElement.name = 'styles['+styleCount+'][packages]['+packageCount+'][image]';
                hiddenInputElement.className = 'package-image';
                hiddenInputElement.setAttribute('data-package', styleCount+'-'+packageCount);
                hiddenInputElement.value = '';
                newPackage.appendChild(hiddenInputElement);

                // Create button for removing the package
                var removePackageButton = document.createElement('button');
                removePackageButton.type = 'button';
                removePackageButton.className = 'remove-package';
                removePackageButton.innerText = 'Remove Package';
                removePackageButton.addEventListener('click', function() {
                    newPackage.remove();
                });
                newPackage.appendChild(removePackageButton);


                // Create button for adding a new package
                var addVariantButton = document.createElement('button');
                addVariantButton.type = 'button';
                addVariantButton.className = 'add-variant';
                addVariantButton.id = 'add-variant-' + styleCount;
                addVariantButton.innerText = 'Add Variant';
                newPackage.appendChild(addVariantButton);

                // Append the new package to the packages container
                packagesContainer.appendChild(newPackage);

                // Use the featured image URL in your JavaScript code
                var featuredPackageImageURL = jQuery('.preview-image-container:first img').attr('src');
                console.log('Featured Package Image URL:', featuredPackageImageURL);
            });


            jQuery(".add-package-variant").click((e) => {

                // get current package id 
                id = jQuery(e.target).parent(".package").attr("id");
                styleId = jQuery(e.target).parent(".package").parent(".packages-container").parent(".style").attr("id");
                // get current package element

                // get current package parent style
				// var styleId = jQuery(elementPackage).parent(".packages-container").parent(".style").attr("id");
                
                // get current package parent style
                var styleCount = styleId.split("-")[(styleId.split("-")).length - 1];
                var packageCount = id.split("-")[(id.split("-")).length - 1];


                var variantsContainer = document.querySelector('div[data-variant="'+styleCount+'-'+packageCount+'"]');

                console.log(id);
                console.log(styleId);

                // var elementPackage = document.getElementById(id);
                // var variantsContainer = elementPackage.querySelector(".variants-container");
                var variantCount = variantsContainer.querySelectorAll(".package-variant").length;

                var newVaraint = document.createElement('div');
                newVaraint.className = 'package-variant';

                // Variant Image
                var previewImageContainer = document.createElement('div');
                previewImageContainer.className = 'preview-image-container';
                previewImageContainer.setAttribute('data-package-variant', styleCount+'-'+packageCount+'-'+variantCount);

                // Create the image element
                var imageElement = document.createElement('img');
                imageElement.src = ''; // Set the source of the image
                imageElement.alt = 'Package Image';
                imageElement.style.height = '200px'; // Set the height of the image
                imageElement.style.width = '300px'; // Set the width of the image

                // Append the image element to the div container
                previewImageContainer.appendChild(imageElement);

                // Assuming newPackage is defined somewhere
                newVaraint.appendChild(previewImageContainer); // Append the div container to the parent element
                
                // Create the upload image button
                var uploadImageButton = document.createElement('button');
                uploadImageButton.type = 'button';
                uploadImageButton.className = 'upload-image-button';
                uploadImageButton.setAttribute('data-package', packageCount);
                uploadImageButton.setAttribute('data-style', styleCount);
                uploadImageButton.setAttribute('data-varaint', variantCount);
                uploadImageButton.innerText = 'Upload Image';
                newVaraint.appendChild(uploadImageButton);
                
                jQuery(newVaraint).on('click', '.upload-image-button', function(e) {
                    e.preventDefault();
                    var packageKey = jQuery(this).data('package');
                    var styleKey = jQuery(this).data('style');
                    var varaintKey = jQuery(this).data('varaint');
                    
                    console.log(styleKey);
                    console.log(packageKey);
                    console.log(varaintKey);

                    var customUploader = wp.media({
                        title: 'Choose an Image',
                        library: {
                            type: 'image'
                        },
                        button: {
                            text: 'Choose Image'
                        },
                        multiple: false
                    });

                    customUploader.on('select', function() {
                        var attachment = customUploader.state().get('selection').first().toJSON();
                        console.log(attachment);
                        jQuery('.package-image[data-package-variant="' + styleKey + "-" + packageKey + '-'+ varaintKey +'"]').val(attachment.id);
                        jQuery('.preview-image-container[data-package-variant="' + styleKey + "-" + packageKey + '-'+ varaintKey +'"] img').attr('src', attachment.url);
                    });

                    customUploader.open();
                });


                // Create input for Package Name
                var variantNameInput = document.createElement('input');
                variantNameInput.type = 'text';
                variantNameInput.name = 'styles[' + styleCount + '][packages][' + packageCount + '][variant]['+variantCount+'][name]';
                variantNameInput.placeholder = 'Variant Name';
                newVaraint.appendChild(variantNameInput);

                // Create the hidden input element
                var hiddenInputElement = document.createElement('input');
                hiddenInputElement.type = 'hidden';
                hiddenInputElement.name = 'styles['+styleCount+'][packages]['+packageCount+'][variant]['+variantCount+'][image]';
                hiddenInputElement.className = 'package-image';
                hiddenInputElement.setAttribute('data-package-variant', styleCount+'-'+packageCount+'-'+variantCount);
                hiddenInputElement.value = '';
                newVaraint.appendChild(hiddenInputElement);

                // Create button for removing the package
                var removePackageButton = document.createElement('button');
                removePackageButton.type = 'button';
                removePackageButton.className = 'remove-variant';
                removePackageButton.innerText = 'Remove Variant';
                removePackageButton.addEventListener('click', function() {
                    newVaraint.remove();
                });
                newVaraint.appendChild(removePackageButton);

                // Append the new package to the packages container
                variantsContainer.appendChild(newVaraint);

                // Use the featured image URL in your JavaScript code
                var featuredPackageImageURL = jQuery('.preview-image-container:first img').attr('src');
                console.log('Featured Package Image URL:', featuredPackageImageURL);
            });

            jQuery('.upload-image-button').on('click', function(e) {
                e.preventDefault();
				
                var packageKey = jQuery(this).data('package');
                var styleKey = jQuery(this).data('style');
                var variantKey = jQuery(this).data('variant');
				
                console.log(packageKey);
                console.log(styleKey);
                console.log(variantKey);
                
                console.log(jQuery(this));
                console.log(e.target);
                var customUploader = wp.media({
                    title: 'Choose an Image',
                    library: {
                        type: 'image'
                    },
                    button: {
                        text: 'Choose Image'
                    },
                    multiple: false
                });

                customUploader.on('select', function() {
                    var attachment = customUploader.state().get('selection').first().toJSON();

                    if(variantKey !== ""){
                        jQuery('.variant-image[data-package-variant="' + styleKey + "-" + packageKey + '-'+variantKey+'"]').val(attachment.id);
                        jQuery('.preview-image-container[data-package-variant="' + styleKey + "-" + packageKey + '-'+variantKey+'"] img').attr('src', attachment.url);
                    }else{
                        jQuery('.package-image[data-package="' + styleKey + "-" + packageKey + '"]').val(attachment.id);
                        jQuery('.preview-image-container[data-package="' + styleKey + "-" + packageKey + '"] img').attr('src', attachment.url);
                    }
                });

                customUploader.open();
            });

            jQuery("#updateproduct").click((e) => {
                jQuery(e.target).val("Loading...");
				jQuery("#loader-gif").html("<img src='/wp-admin/images/spinner.gif'/>");
                jQuery.ajax({
                    url: 'https://' + window.location.host + '/wp-json/nayagroup-custom/v1/update-product/', // Adjust the endpoint URL
                    type: 'POST',
                    data: {},
                    contentType: 'application/json',
                    success: function(response) {
                        jQuery(e.target).val("Update products");
                        console.log(response);
                        // window.location.href = response.data;
                    },
                    error: function(error) {
                        // alert(error.responseText);
                        console.log(error.responseText);
                        jQuery(e.target).val("Products updated");
						setTimeout(()=>{
	                        jQuery(e.target).val("Update products");
						}, 1000)
						jQuery("#loader-gif").html("");
                    }
                });
            });


            jQuery(".remove-package").click((e) => {
                jQuery(e.target).parent(".package").remove();
            });

            jQuery(".remove-style").click((e) => {
                jQuery(e.target).parent(".style").remove();
            });
			
			
            jQuery(".remove-package-variant").click((e) => {
                jQuery(e.target).parent(".package-variant").remove();
            });
        </script>
    </div>
