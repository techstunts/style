document.addEventListener('DOMContentLoaded', function() {
  var fetchButton = document.getElementById('fetch');
      
  fetchButton.addEventListener('click', function() {

    chrome.tabs.getSelected(null, function(tab) {
      d = document;

			chrome.tabs.executeScript(
				null,
				{
					code: '\
					var prod_name = document.getElementById(\'prod_name_hide\').value;\
					var prod_price = document.getElementsByClassName(\'product-price\')[0].getElementsByTagName(\'strong\')[0].innerHTML;\
					var prod_desc = document.getElementById(\'product-desc\').innerText;\
					var a_tags = document.getElementsByClassName(\'thumb-views\')[0].getElementsByTagName(\'a\');\
					var category = document.getElementById(\'product-desc\').getElementsByClassName(\'short_detail\')[0].getElementsByTagName(\'a\')[0].innerText;\
					var brand = document.getElementById(\'product-desc\').getElementsByClassName(\'short_detail\')[0].getElementsByTagName(\'a\')[1].innerText;\
					var gender = document.getElementById(\'breadcrumb\').getElementsByClassName(\'breadcrumb-link\')[1].getElementsByTagName(\'span\')[0].innerText;\
					if(gender == "Women")\
						gender = "Female";\
					if(gender == "Men")\
						gender = "Male";\
					\
					var img_links = [];\
					for(var i=0; i<a_tags.length; i++){\
						var rel = a_tags[i].rel.replace(/\",\"/g,", ");\
						img_links.push(rel.split(\',\')[0].split(\'\":\"\')[1]);\
					}\
					var r = [prod_name, prod_price, prod_desc, img_links, \'koovs\', category, brand, gender];\
					r;\
					'
				},
				function(results){
					document.getElementById('name').value = results[0][0];
					document.getElementById('price').value = results[0][1];
					document.getElementById('desc').innerHTML = results[0][2];
					document.getElementById('images').innerHTML = results[0][3];
					document.getElementById('url').value = tab.url;
					document.getElementById('merchant').value = results[0][4];
					document.getElementById('category').value = results[0][5];
					document.getElementById('brand').value = results[0][6];
					document.getElementById('gender').value = results[0][7];

					
					var imagesDiv = document.getElementsByClassName('images')[0];

					//Remove previously added image tags
					var images = imagesDiv.getElementsByTagName('img');
					while(images.length > 0){
						images[0].parentNode.removeChild(images[0]);
					}
					
					//Remove previously added image url input tags
					var image_urls = imagesDiv.getElementsByClassName('prod_image');
					while(image_urls.length > 0){
						image_urls[0].parentNode.removeChild(image_urls[0]);
					}
					
					
					//Add image tags and image url input tags
					for(cnt in results[0][3]){
						var image = document.createElement('img');
						image.src=results[0][3][cnt];
						imagesDiv.appendChild(image);
						
						var input = document.createElement('input');
						input.value = results[0][3][cnt];
						input.name = "image" + cnt;
						input.className = "prod_image";
						imagesDiv.appendChild(input);
					}
					
				}
			);


    });
  }, false);
  
  var saveButton = document.getElementById('save');
  
  saveButton.addEventListener('click', function() {


			var kvpairs = [];
			var input_elements = document.getElementsByTagName('input');
			for (var i = 0; i < input_elements.length; i++ ) {
				 var e = input_elements[i];
				 kvpairs.push(encodeURIComponent(e.name) + "=" + encodeURIComponent(e.value));
			}
			var queryString = kvpairs.join("&");

		  	var url = "http://stylist.istyleyou.in/product/create";
	  		//var url = "http://stylist.istyleyou.loc/product/create";
			url = url + "?" + queryString;
			//alert(url);
			console.log(url);
			
			var xmlDoc = new XMLHttpRequest();
			xmlDoc.open('GET', url, true);

			xmlDoc.onreadystatechange = function() {
				if (xmlDoc.readyState === 4 && xmlDoc.status === 200) {
					console.log(xmlDoc.responseText);
					console.log(xmlDoc.responseText[0]);
					var response = JSON.parse(xmlDoc.responseText);
					if(response[0])
					{
						var alink = document.createElement('a');
						alink.href = response[1];
						alink.text = 'Check product';
						alink.target = 'New';
						saveButton.parentNode.appendChild(alink); 
						alert("Product saved");
					}
				}
			}

			xmlDoc.send();
			
	}, false);
	
}, false);
