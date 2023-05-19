//Parsledz mobile navigaciju
function toggleNavbar() {
  var mobileNavbar = document.getElementById("mobileNavbar");
  if(mobileNavbar.style.display === "block") {
    mobileNavbar.style.display = "none";
  } else {
    mobileNavbar.style.display = "block";
  }
}
//Parsledz mobile navigacijas apakskategorijas
function toggleSubmenu() {
  document.querySelectorAll('.dropdown-btn').forEach(function(dropdownBtn) {
    dropdownBtn.onclick = function() {
      dropdownBtn.classList.toggle('active');
      var dropdownContent = dropdownBtn.nextElementSibling;
      if(dropdownContent && dropdownContent.style) {
        if(dropdownContent.style.display === 'block') {
          dropdownContent.style.display = 'none';
        } else {
          dropdownContent.style.display = 'block';
        }
      }
    };
  });
}
//Parada/paslepj formu
function displayProfileUpdateForm() {
  var updateForm = document.querySelector('.update-profile-form');
  updateForm.style.display = 'block'; 
}
function closeProfileUpdateForm() {
  var closeForm = document.querySelector('.update-profile-form');
  closeForm.style.display = 'none';
}
//daudzuma funkcijas
var incrementQtys = document.querySelectorAll('.increment-btn');
incrementQtys.forEach(function(incrementQty) {
  incrementQty.addEventListener('click', function(e) {
    e.preventDefault();
    incrementQtyOperator(e);
  });
});
var decrementQtys = document.querySelectorAll('.decrement-btn');
decrementQtys.forEach(function(decrementQty) {
  decrementQty.addEventListener('click', function(e) {
    e.preventDefault();
    decrementQtyOperator(e);
  });
});
function incrementQtyOperator(e) {
  var btn = e.target;
  var productData = btn.closest('.row');
  var inputQty = productData.querySelector('.input-qty');
  var value = parseInt(inputQty.value, 10);
  value = isNaN(value) ? 0 : value;
  if (value < 10) {
    value++;
    inputQty.value = value;
  }
}
function decrementQtyOperator(e) {
  var btn = e.target;
  var productData = btn.closest('.row');
  var inputQty = productData.querySelector('.input-qty');
  var value = parseInt(inputQty.value, 10);
  value = isNaN(value) ? 0 : value;
  if (value > 1) {
    value--;
    inputQty.value = value;
  }
}
//daudzuma funkcijas pirkuma grozam
document.addEventListener("DOMContentLoaded", function() {
  var incrementButtons = document.querySelectorAll('.increment-cart-btn');
  incrementButtons.forEach(function(incrementButton) {
    incrementButton.addEventListener('click', function(e) {
      e.preventDefault();
      var productData = this.closest('.table-row');
      var inputQty = productData.querySelector('.input-qty');
      var value = parseInt(inputQty.value, 10);
      value = isNaN(value) ? 0 : value;
      if (value < 10) {
        value++;
        inputQty.value = value;
        updatePrice(productData, value);
      }
    });
  });
  var decrementButtons = document.querySelectorAll('.decrement-cart-btn');
  decrementButtons.forEach(function(decrementButton) {
    decrementButton.addEventListener('click', function(e) {
      e.preventDefault();
      var productData = this.closest('.table-row');
      var inputQty = productData.querySelector('.input-qty');
      var value = parseInt(inputQty.value, 10);
      value = isNaN(value) ? 0 : value;
      if (value > 1) {
        value--;
        inputQty.value = value;
        updatePrice(productData, value);
      }
    });
  });
  function updatePrice(productData, count) {
    var priceElement = productData.querySelector('.discount-price-active');
    var price;
    if(priceElement) {
      price = parseFloat(priceElement.innerHTML);
    } else {
      priceElement = productData.querySelector('.product-price')
      price = parseFloat(priceElement.innerHTML);
    }
    var totalPrice = (price * count).toFixed(2);
    productData.querySelector('.total-price').innerHTML = totalPrice.toString().replace(".",",") + "€";
  }
});
//Parada/slepj paroli
function togglePassword(inputId, toggleId) {
  const passwordField = document.getElementById(inputId);
  const togglePassword = document.getElementById(toggleId);
  const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
  passwordField.setAttribute('type', type);
  togglePassword.querySelector('i').classList.toggle('fa-eye');
  togglePassword.querySelector('i').classList.toggle('fa-eye-slash');
};
//atlasa produktus pec filtriem
function sortFilter() {
  var select = document.querySelector("select[name='sortProducts']");
  var selectedOption = select.options[select.selectedIndex].value;
  var currentUrl = new URL(window.location.href);
  var searchParams = new URLSearchParams(currentUrl.search);
  searchParams.set('sortProducts', selectedOption);
  var newUrl = currentUrl.protocol + '//' + currentUrl.host + currentUrl.pathname + '?' + searchParams.toString();
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function() {
   if(this.readyState === 4 && this.status === 200) {
      var responseHTML = xhr.responseText;
      var parser = new DOMParser;
      var responseDoc = parser.parseFromString(responseHTML, "text/html");
      var productsContainer = responseDoc.querySelector(".product-page-products");
      var newProductsContainer = document.querySelector(".product-page-products");
      newProductsContainer.innerHTML = productsContainer.innerHTML;
    }
  };
  xhr.open("POST", newUrl, true);
  xhr.send();
};
//mekle produktus
  function search() {
    var searchWord = "";
    searchWord = document.querySelector("#searchWord").value;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if(this.readyState == 4) {
        if(this.status == 200) {
          var products = JSON.parse(this.responseText);
          var resultString = "";
          if(searchWord === "") {
            resultString = "";
          } else if(products.length > 0) {
            for(var i = 0; i < products.length; i++) {
              if(products[i].name.toLowerCase().indexOf(searchWord.toLowerCase()) !== -1) {
                resultString += '<div class="search-result">'
                resultString += '<a href="product-single.php?product='+ products[i].slug+'">';
                resultString += '<img src="assets/img/'+products[i].featuredImage+'" alt="" width="50px" height="50px" class="product-img"' + products[i].name + '">';
                resultString += '<span class="product-name">' + products[i].name + "</span>";
                resultString += '</a>';
                resultString += '</div>';
              }
            }
          } else {
            resultString = "Produkts netika atrasts";
            document.querySelector("#results").style.padding = "20px 50px 20px 30px";
          }
          document.querySelector("#results").innerHTML = resultString;
        } else {
          document.querySelector("#results").innerHTML = "Kļūda" + this.statusText;
        }
      }
    };
    document.querySelector("#results").style.display = "block";
    xhttp.open("GET", "search.php?searchWord=" + searchWord, true);
    xhttp.send();
    //aizver meklesanas rezultatus, ja uzspiez arpus ievades lauka
    document.addEventListener("click", function(event) {
      var searchBox = document.getElementById("searchBox");
      var results = document.getElementById("results");
      if(!searchBox.contains(event.target) && results.style.display === 'block') {
        results.style.display = "none";
      }
    });
  };
//attelu galerija
const featuredImage = document.getElementById("featuredImage");
const thumbnail = document.getElementsByClassName("thumbnail");
const imageContainer = document.getElementById("imageContainer");
const galleryBox = document.getElementById("galleryBox");
const previousButton = document.getElementById("previousButton");
const nextButton = document.getElementById("nextButton");
const count = document.getElementById("imageCount");
let currentIndex = 0;
let totalImages = thumbnail.length;
  if(totalImages > 0) {
    thumbnail[0].classList.add("active");
    imageContainer.src = thumbnail[0].src;
    featuredImage.src = thumbnail[0].src;
    count.innerHTML = "1 / " + totalImages;
  }
  //change featured image when thumbnail is clicked
  for(let i=0; i<thumbnail.length; i++) {
    thumbnail[i].addEventListener("click", function() {
      featuredImage.src = thumbnail[i].src;
      currentIndex = i;
      updateImage();
    });
  }
function updateFeaturedImage() {
  imageContainer.style.display = "block";
  //imageContainer.style.transition = "transition: 0.6s ease";
}
function updateImage() {
  imageContainer.style.display = "block";
  imageContainer.src = thumbnail[currentIndex].src;
  featuredImage.src = thumbnail[currentIndex].src;
  count.innerHTML = (currentIndex + 1) + " / " + totalImages;
  for(let i=0; i<thumbnail.length; i++) {
    thumbnail[i].classList.remove("active");
  }
  thumbnail[currentIndex].classList.add("active");
} 
function previousButtonClick() {
  if(currentIndex === 0) {
    currentIndex = thumbnail.length - 1;
  } else {
    currentIndex--;
  }
  updateImage();
}
function nextButtonClick() {
  if(currentIndex === thumbnail.length - 1) {
    currentIndex = 0;
  } else {
    currentIndex++;
  }
  updateImage();
}
function galleryOpen() {
  if(thumbnail.length > 0) { 
    galleryBox.style.display = "flex";
    updateImage();
  } else {}
}
function galleryClose() {
  galleryBox.style.display = "none";
}
//parada/paslepj ievades laukus checkout lapa; apreikina kopejo summu
var deliverySelected = false;
function handleShippingOption() {
  const adressFields = document.getElementById("adress-fields");
  const paymentMethodStore = document.getElementById("paymentMethodStore");
  var orderBtn = document.getElementById("submitOrder");
  var totalPrice = document.getElementById("totalPrice");
  var totalPricePayment = document.getElementById("totalPricePayment");
  var currentPrice = parseFloat(totalPricePayment.value);
  if(document.getElementById("shipping_method_store").checked) {
    adressFields.style.display = "none";
    paymentMethodStore.style.display = "block";
    orderBtn.removeAttribute("disabled");
    if(deliverySelected == true) {
      totalPricePayment.value = (totalPricePayment.value - 9.99).toFixed(2);
      totalPrice.innerHTML = totalPricePayment.value.replace(".",",") + "€";
      deliverySelected = false;
    }
  } else {
    adressFields.style.display = "block";
    paymentMethodStore.style.display = "none";
    if(document.getElementById("shipping_method_delivery").checked) {
      orderBtn.setAttribute("disabled", "disabled");
      if(deliverySelected == false) {
      var newPrice = currentPrice + 9.99;
      totalPrice.innerHTML = newPrice.toFixed(2).replace(".",",") + "€";
      totalPricePayment.value = newPrice.toFixed(2);
      deliverySelected = true;
      }
    } else {
      orderBtn.removeAttribute("disabled");
    }
  }
  localStorage.setItem("adressFieldsVisibility", adressFields.style.display);
};
// Apstrada apmaksas metodes
function handlePaymentOption() {
  var orderBtn = document.getElementById("submitOrder");
  var paymentButton = document.getElementById("paypal-payment-button");
  if(document.getElementById("payment_method_paypal").checked) {
    paymentButton.style.display = "block";
    orderBtn.setAttribute("disabled", "disabled");
  } else {
    paymentButton.style.display = "none";
    if(document.getElementById("payment_method_store").checked) {
      orderBtn.removeAttribute("disabled");
    } else {
      orderBtn.setAttribute("disabled", "disabled");
    }
  }
};

