// Update Selections by URL

// if(window.location.pathname === '/subscription-creation/'){
// 	const queryString = window.location.search;
// 	if(queryString){
// 		const urlParams = new URLSearchParams(queryString);
// 		const style = urlParams.get('style');
// 		localStorage.setItem("darcey-style",style);
// 		const package = urlParams.get('package');
// 		localStorage.setItem("darcey-size",package);
// 	}
// }


// Load localstorage value
window.onload = () => {
	if(window.location.pathname === '/subscription-creation/'){
		const queryString = window.location.search;
		if(queryString){
			const urlParams = new URLSearchParams(queryString);
			var style = urlParams.get('style');
			var size = urlParams.get('package');

			localStorage.setItem("darcey-style",style);
			localStorage.setItem("darcey-size",size);
			
			jQuery(".select-style").each((index,element)=>{
				if(jQuery(element).find("h2").html() == style){
					jQuery(element).addClass("active-select-style");
					changeSizes(style);
				}else{	
				    document.querySelector('.darcey-style').scrollIntoView({ behavior: 'smooth' });
				}
			})
			jQuery(".select-size").each((index,element)=>{
				if(jQuery(element).find("h2").html() == size){
					jQuery(element).addClass("active-select-size");
				}
			})
			jQuery(".select-color").each((index,element)=>{
				if(jQuery(element).find("h2").html() == color){
					jQuery(element).addClass("active-select-color");
				}
			})
			document.querySelector('.darcey-frequent-delivery').scrollIntoView({ behavior: 'smooth' });
		}else{
			var style = localStorage.getItem("darcey-style");
			var size = localStorage.getItem("darcey-size");
			var color = localStorage.getItem("darcey-color");
			jQuery(".select-style").each((index,element)=>{
				if(jQuery(element).find("h2").html() == style){
					jQuery(element).addClass("active-select-style");
					changeSizes(style);
				}else{
					
				}
			})
			jQuery(".select-size").each((index,element)=>{
				if(jQuery(element).find("h2").html() == size){
					jQuery(element).addClass("active-select-size");
					changeColor(style, size);
				}
			})
			jQuery(".select-color").each((index,element)=>{
				if(jQuery(element).find("h2").html() == color){
					jQuery(element).addClass("active-select-color");
				}
			})
		}
	}
	
	
	var frequentDelivery = localStorage.getItem("darcey-frequent-delivery");
	jQuery(".select-frequent-delivery").each((index,element)=>{
		if(jQuery(element).find("h2").html() == frequentDelivery){
			jQuery(element).addClass("active-select-frequent-delivery");
		}
	})
	
	var lastSubscription = localStorage.getItem("darcey-last-subscription");
	jQuery(".select-last-subscription").each((index,element)=>{
		if(jQuery(element).find("h2").html() == lastSubscription){
			jQuery(element).addClass("active-select-last-subscription");
		}
	})
	
	var deliveryTime = localStorage.getItem("darcey-delivery-time");
	jQuery(".select-delivery-time").each((index,element)=>{
		if(jQuery(element).find("h2").html() == deliveryTime){
			jQuery(element).addClass("active-select-delivery-time");
		}
	})
	
	var startFrom = localStorage.getItem("darcey-start-from");
	if(startFrom){
		var parts = startFrom.split('/');
	    var formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
		jQuery("#dateInput").val(formattedDate);
	}
		
	var msg = localStorage.getItem("darcey-message");
	if(msg){
		jQuery("#packgeMessage").val(msg);
	}
	
	var paymentSystem = localStorage.getItem("payment-system");
	
	if(paymentSystem === "1"){
		jQuery("#paymentSystem").attr("checked", "checked");
		jQuery(".per-delivery").css({"font-weight": "400"});
		jQuery(".per-month").css({"font-weight": "bold"});
	}else{
		jQuery("#paymentSystem").attr("checked", null);
		jQuery(".per-delivery").css({"font-weight": "bold"});
		jQuery(".per-month").css({"font-weight": "400"});
	}
	
	updateData();
	
	// disable past date
	var today = new Date().toISOString().split('T')[0];
	if(document.getElementById("dateInput")){document.getElementById("dateInput").setAttribute("min", today);}


}



// form data save in localstorage
jQuery(".select-style").click(function() {
    var style = jQuery(this).find("h2").html();
	localStorage.setItem("darcey-style",style);
	
	jQuery(".select-style").removeClass("active-select-style");
	jQuery(this).addClass("active-select-style");
    document.querySelector('.darcey-size').scrollIntoView({ behavior: 'smooth' });
	updateData();
	changeSizes(style);
});

// setInterval(()=>{
	jQuery(".select-size").click(function() {
		var size = jQuery(this).find("h2").html();
		localStorage.setItem("darcey-size",size);
		var style = localStorage.getItem("darcey-style");
		
		jQuery(".select-size").removeClass("active-select-size");
		jQuery(this).addClass("active-select-size");
		document.querySelector('.darcey-color').scrollIntoView({ behavior: 'smooth' });
		updateData();
		changeColor(style, size);
	});
// }, 1000);

// setInterval(()=>{
	jQuery(".select-color").click(function() {
		var color = jQuery(this).find("h2").html();
		localStorage.setItem("darcey-color",color);
		
		jQuery(".select-color").removeClass("active-select-color");
		jQuery(this).addClass("active-select-color");
		document.querySelector('.darcey-frequent-delivery').scrollIntoView({ behavior: 'smooth' });
		updateData();
	});
// }, 1000);


jQuery(".select-frequent-delivery").click(function() {
    var frequentDelivery = jQuery(this).find("h2").html();
	localStorage.setItem("darcey-frequent-delivery",frequentDelivery);
	
	jQuery(".select-frequent-delivery").removeClass("active-select-frequent-delivery");
	jQuery(this).addClass("active-select-frequent-delivery");
    document.querySelector('.darcey-last-subscription').scrollIntoView({ behavior: 'smooth' });
	updateData();
});


jQuery(".select-last-subscription").click(function() {
    var lastSubscription = jQuery(this).find("h2").html();
	localStorage.setItem("darcey-last-subscription",lastSubscription);
	
	jQuery(".select-last-subscription").removeClass("active-select-last-subscription");
	jQuery(this).addClass("active-select-last-subscription");
    document.querySelector('.darcey-start-from').scrollIntoView({ behavior: 'smooth' });
	updateData();
});


jQuery(".select-delivery-time").click(function() {
    var deliveryTime = jQuery(this).find("h2").html();
	localStorage.setItem("darcey-delivery-time",deliveryTime);
	
	jQuery(".select-delivery-time").removeClass("active-select-delivery-time");
	jQuery(this).addClass("active-select-delivery-time");
    document.querySelector('.darcey-message-box').scrollIntoView({ behavior: 'smooth' });
	updateData();
});

jQuery(".next-messsage").click(function() {
    document.querySelector('.darcey-form-details').scrollIntoView({top:20, behavior: 'smooth' });
	updateData();
});


jQuery("#dateInput").change(function() {
	var startFrom = new Date(jQuery(this).val()).toLocaleDateString('en-GB');
	localStorage.setItem("darcey-start-from",startFrom);
    document.querySelector('.darcey-delivery-time').scrollIntoView({ behavior: 'smooth' });
	updateData();
});


jQuery("#packgeMessage").change(function() {
    var msg = jQuery(this).val();
	localStorage.setItem("darcey-message",msg);
	updateData();
});

jQuery("#paymentSystem").change(function() {
    var paymentSystem = jQuery(this).prop("checked") ? 1 : 0;
	if(paymentSystem){
		jQuery(".per-delivery").css({"font-weight": "400"});
		jQuery(".per-month").css({"font-weight": "bold"});
	   localStorage.setItem("payment-system",1);
	}else{
		jQuery(".per-delivery").css({"font-weight": "bold"});
		jQuery(".per-month").css({"font-weight": "400"});
	   localStorage.setItem("payment-system",0);
	}
	updateData();
});

// Move edit
//  
if(document.querySelector('.edit-style')){
	document.querySelector('.edit-style').addEventListener('click', function() {
		document.querySelector('.darcey-style').scrollIntoView({ behavior: 'smooth' });
	});
}
if(document.querySelector('.edit-size')){
	document.querySelector('.edit-size').addEventListener('click', function() {
		document.querySelector('.darcey-size').scrollIntoView({ behavior: 'smooth' });
	});
}
if(document.querySelector('.edit-color')){
	document.querySelector('.edit-color').addEventListener('click', function() {
		document.querySelector('.darcey-color').scrollIntoView({ behavior: 'smooth' });
	});
}
if(document.querySelector('.edit-frequency')){
	document.querySelector('.edit-frequency').addEventListener('click', function() {
		document.querySelector('.darcey-frequent-delivery').scrollIntoView({ behavior: 'smooth' });
	});
}
if(document.querySelector('.edit-duration')){
	document.querySelector('.edit-duration').addEventListener('click', function() {
		document.querySelector('.darcey-last-subscription').scrollIntoView({ behavior: 'smooth' });
	});
}
if(document.querySelector('.edit-delivery-date')){
	document.querySelector('.edit-delivery-date').addEventListener('click', function() {
		document.querySelector('.darcey-start-from').scrollIntoView({ behavior: 'smooth' });
	});
}
if(document.querySelector('.edit-duration-time')){
	document.querySelector('.edit-duration-time').addEventListener('click', function() {
		document.querySelector('.darcey-delivery-time').scrollIntoView({ behavior: 'smooth' });
	});
}



function updateData(){
	var style = localStorage.getItem("darcey-style");
	if(style){
		jQuery(".style-info-data").html(style);
	}
	
	var size = localStorage.getItem("darcey-size");
	if(size){
		jQuery(".package-info-data").html(size);
	}

	var color = localStorage.getItem("darcey-color");
	if(color){
		jQuery(".color-info-data").html(color);
	}
	
	var frequentDelivery = localStorage.getItem("darcey-frequent-delivery");
	if(frequentDelivery){
		jQuery(".subscription-frequency-info-data").html(frequentDelivery);
	}
	
	var lastSubscription = localStorage.getItem("darcey-last-subscription");
	if(lastSubscription){
		jQuery(".subscription-duration-info-data").html(lastSubscription);
	}
	
	var startFrom = localStorage.getItem("darcey-start-from");
	if(startFrom){
		jQuery(".delivery-date-info-data").html(startFrom);
	}
	
	var deliveryTime = localStorage.getItem("darcey-delivery-time");
	if(deliveryTime){
		jQuery(".delivery-time-info-data").html(deliveryTime);
	}
}

jQuery(".process-next").click(()=>{

	jQuery("#loading").css({"display":"flex"});
	
	
	var darceyStyle = localStorage.getItem("darcey-style");
	if(!darceyStyle){
		document.querySelector('.darcey-style').scrollIntoView({ behavior: 'smooth' });
		jQuery("#loading").css({"display":"none"});
		return;
	}

	var darceySize = localStorage.getItem("darcey-size");
	if(!darceySize){
		document.querySelector('.darcey-size').scrollIntoView({ behavior: 'smooth' });
		jQuery("#loading").css({"display":"none"});
		return;
	}

	var darceyColor = localStorage.getItem("darcey-color");
	if(!darceyColor){
		if(jQuery(".darcey-color").css('display') != 'none'){
		   document.querySelector('.darcey-color').scrollIntoView({ behavior: 'smooth' });
			jQuery("#loading").css({"display":"none"});
			return;
		}
	}
	
	var darceyFrequentDelivery = localStorage.getItem("darcey-frequent-delivery");
	if(!darceyFrequentDelivery){
		document.querySelector('.darcey-frequent-delivery').scrollIntoView({ behavior: 'smooth' });
		jQuery("#loading").css({"display":"none"});
		return;
	}

	var darceyLastSubscription = localStorage.getItem("darcey-last-subscription");
	if(!darceyLastSubscription){
		document.querySelector('.darcey-last-subscription').scrollIntoView({ behavior: 'smooth' });
		jQuery("#loading").css({"display":"none"});
		return;
	}

	var darceyDeliveryTime = localStorage.getItem("darcey-delivery-time");
	if(!darceyDeliveryTime){
		document.querySelector('.darcey-delivery-time').scrollIntoView({ behavior: 'smooth' });
		jQuery("#loading").css({"display":"none"});
		return;
	}
	
	var darceyStartFrom = localStorage.getItem("darcey-start-from");
	if(!darceyStartFrom){
		document.querySelector('.darcey-start-from').scrollIntoView({ behavior: 'smooth' });
		jQuery("#loading").css({"display":"none"});
		return;
	}
	
	var darceyMessage = localStorage.getItem("darcey-message");
	// if(!darceyMessage){
	// 	document.querySelector('.darcey-message-box').scrollIntoView({ behavior: 'smooth' });
	// 	jQuery("#loading").css({"display":"none"});
	// 	return;
	// }
	
	var paymentSystem = localStorage.getItem("payment-system");

	
	var requestData = {
		darceyStyle:darceyStyle,
		darceySize:darceySize,
		darceyColor:darceyColor,
		darceyFrequentDelivery:darceyFrequentDelivery,
		darceyLastSubscription:darceyLastSubscription,
		darceyDeliveryTime:darceyDeliveryTime,
		darceyStartFrom:darceyStartFrom,
		darceyMessage:darceyMessage,
		paymentSystem:paymentSystem?paymentSystem:0
	};

	// setTimeout(()=>{
		jQuery.ajax({
			url: 'https://'+window.location.host+'/wp-json/nayagroup-custom/v1/add-to-cart/', // Adjust the endpoint URL
			type: 'POST',
			data: JSON.stringify(requestData),
			contentType: 'application/json'
		}).always(function( response ) {
			jQuery("#loading").css({"display":"none"});

    
			
			localStorage.removeItem("darcey-style");
			localStorage.removeItem("darcey-size");
			localStorage.removeItem("darcey-frequent-delivery");
			localStorage.removeItem("darcey-last-subscription");
			localStorage.removeItem("darcey-delivery-time");
			localStorage.removeItem("darcey-start-from");
			localStorage.removeItem("darcey-message");
			localStorage.removeItem("payment-system");

			window.location.href = "/checkout";
			console.log("Errors",response);
		  });
	// },500);

    // document.querySelector('.darcey-style').scrollIntoView({ behavior: 'smooth' });
});


function changeSizes(style){
	
	jQuery("#loading").css({"display":"flex"});

	jQuery.ajax({
		url: 'https://'+window.location.host+'/wp-json/nayagroup-custom/v1/get-package/', // Adjust the endpoint URL
		type: 'POST',
		data: JSON.stringify({}),
		contentType: 'application/json',
		success: function(response) {
			var html = "";
			var size = localStorage.getItem("darcey-size");
			response.forEach(element => {
				if(element.name == style){
					element.packages.forEach(elementpackages => {
						if(elementpackages.name == size){
							html += '<div class="wp-block-uagb-container cursor-pointer select-size uagb-block-c923626a active-select-size"><h2 class="wp-block-heading has-text-align-left margin-0 has-medium-font-size" style="font-style:normal;font-weight:700">'+elementpackages.name+'</h2><p>Initial Price: '+(parseInt(elementpackages.price) + parseInt(elementpackages.initial_price)).toString()+' AED <br>Suceeding Price: '+elementpackages.price+' AED</p></div>';
						}else{
							html += '<div class="wp-block-uagb-container cursor-pointer select-size uagb-block-c923626a"><h2 class="wp-block-heading has-text-align-left margin-0 has-medium-font-size" style="font-style:normal;font-weight:700">'+elementpackages.name+'</h2><p>Initial Price: '+(parseInt(elementpackages.price) + parseInt(elementpackages.initial_price)).toString()+' AED <br>Suceeding Price: '+elementpackages.price+' AED</p></div>';
						}
					});
				}
				
			});

			jQuery(".package-sizes").html(html);
			// window.location.href = response.data;
			jQuery("#loading").css({"display":"none"});

			
			jQuery(".select-size").click(function() {
				var size = jQuery(this).find("h2").html();
				localStorage.setItem("darcey-size",size);
				var style = localStorage.getItem("darcey-style");
				
				jQuery(".select-size").removeClass("active-select-size");
				jQuery(this).addClass("active-select-size");
				document.querySelector('.darcey-color').scrollIntoView({ behavior: 'smooth' });
				updateData();
				changeColor(style, size);
			});
		},
		error: function(error) {
			// alert(error.responseText);
			console.log(error.responseText);
			jQuery("#loading").css({"display":"none"});
		}
	});
}



function changeColor(style, size){
	
	jQuery("#loading").css({"display":"flex"});

	jQuery.ajax({
		url: 'https://'+window.location.host+'/wp-json/nayagroup-custom/v1/get-package/', // Adjust the endpoint URL
		type: 'POST',
		data: JSON.stringify({}),
		contentType: 'application/json',
		success: function(response) {
			var html = "";
			var variants = null;
			var color = localStorage.getItem("darcey-color");
			response.forEach(element => {
				if(element.name == style){
					element.packages.forEach(elementpackages => {
						if(elementpackages.name == size){
							if(elementpackages.variant){
								jQuery(".darcey-color").show();
								elementpackages.variant.forEach(elementvariant => {
									if(elementvariant.name == color){
										html += '<div class="wp-block-uagb-container cursor-pointer select-color uagb-block-93dbe845"><div class="wp-block-uagb-image uagb-block-08a24fa0 wp-block-uagb-image--layout-default wp-block-uagb-image--effect-static wp-block-uagb-image--align-none"><figure class="wp-block-uagb-image__figure"><img src="'+(elementvariant.image?elementvariant.image:"https://stagingnaya.wpenginepowered.com/wp-content/uploads/2024/04/asdfasdasdfa.png")+'" alt="'+elementvariant.name+'" class="uag-image-99892" width="1024" height="683" title="" loading="lazy" role="img"></figure></div><h2 class="wp-block-heading has-text-align-center margin-0 has-medium-font-size" style="font-style:normal;font-weight:700">'+elementvariant.name+'</h2></div>';
									}else{
										html += '<div class="wp-block-uagb-container cursor-pointer select-color uagb-block-93dbe845"><div class="wp-block-uagb-image uagb-block-08a24fa0 wp-block-uagb-image--layout-default wp-block-uagb-image--effect-static wp-block-uagb-image--align-none"><figure class="wp-block-uagb-image__figure"><img src="'+(elementvariant.image?elementvariant.image:"https://stagingnaya.wpenginepowered.com/wp-content/uploads/2024/04/asdfasdasdfa.png")+'" alt="'+elementvariant.name+'" class="uag-image-99892" width="1024" height="683" title="" loading="lazy" role="img"></figure></div><h2 class="wp-block-heading has-text-align-center margin-0 has-medium-font-size" style="font-style:normal;font-weight:700">'+elementvariant.name+'</h2></div>';
									}
								});
								
								variants = elementpackages.variant;
								
							}else{
								jQuery(".darcey-color").hide();
								localStorage.removeItem("darcey-color");
							}
						}
					});
				}
				
				
			});
			
			if(variants){
				
				document.querySelector('.darcey-color').scrollIntoView({ behavior: 'smooth' });

				localStorage.removeItem("darcey-color");

				jQuery(".package-variant").html(html);

				jQuery(".select-color").click(function() {
					var color = jQuery(this).find("h2").html();
					localStorage.setItem("darcey-color",color);

					jQuery(".select-color").removeClass("active-select-color");
					jQuery(this).addClass("active-select-color");
					document.querySelector('.darcey-frequent-delivery').scrollIntoView({ behavior: 'smooth' });
					updateData();
				});
			}
			
			jQuery("#loading").css({"display":"none"});
			
		},
		error: function(error) {
			// alert(error.responseText);
			console.log(error.responseText);
			jQuery("#loading").css({"display":"none"});
		}
	});
}