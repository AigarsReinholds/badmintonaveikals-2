/*Paypal integracija*/
 paypal.Buttons({
   createOrder:function(data, actions) {
    var totalPrice = document.getElementById("totalPricePayment").value;
     return actions.order.create({
       purchase_units: [{
         amount: {
           value: totalPrice
         }
       }]
     });
   },
   onClick: function() {
     var name = $("#shipFirstName").val();
     var surname = $("#shipLastName").val();
     var phone = $("#shipPhone").val();
     var adress = $("#shippingAdress").val();
     var city = $("#shippingCity").val();
     var postcode = $("#shippingPostcode").val();
     if (!$("input[name='shippingMethod']").is(":checked")) {
      $(".shippingMethod").text("Piegādes veids ir obligāti jāizvēlas");
      return false;
     } else {
        $(".shippingMethod").text("");
     }
     if(document.getElementById("shipping_method_store").checked) {
       if (name.length == 0) {
         $(".shipFirstName").text("Norēķinu Vārds ir obligātais lauks");
       } else {
          $(".shipFirstName").text("");
       }
       if (surname.length == 0) {
         $(".shipLastName").text("Norēķinu Uzvārds ir obligātais lauks");
       } else {
          $(".shipLastName").text("");
       }
       if (phone.length == 0) {
         $(".shipPhone").text("Norēķinu Tālrunis ir obligātais lauks");
       } 
       else if(phone.length < 7) {
        $(".shipPhone").text("Telefonam ir jāsatur 8 simboli");
        return false;
        } else {
          $(".shipPhone").text("");
       }
       if(name.length == 0 || surname.length == 0 || phone.length == 0) {
         return false;
       }
     }
     else { 
       if (name.length == 0) {
         $(".shipFirstName").text("Norēķinu Vārds ir obligātais lauks");
       } else {
          $(".shipFirstName").text("");
       }
       if (surname.length == 0) {
         $(".shipLastName").text("Norēķinu Uzvārds ir obligātais lauks");
       } else {
          $(".shipLastName").text("");
       }
       if (phone.length == 0) {
         $(".shipPhone").text("Norēķinu Tālrunis ir obligātais lauks");
       } 
       else if(phone.length < 7) {
        $(".shipPhone").text("Telefonam ir jāsatur 8 simboli");
        return false;
        } else {
          $(".shipPhone").text("");
       }
       if (adress.length == 0) {
         $(".shippingAdress").text("Norēķinu Adrese ir obligātais lauks");
       } else {
          $(".shippingAdress").text("");
       }
       if (city.length == 0) {
         $(".shippingCity").text("Norēķinu Pilsēta ir obligātais lauks");
       } else {
          $(".shippingCity").text("");
       }
       if (postcode.length == 0) {
         $(".shippingPostcode").text("Norēķinu Pasta indekss ir obligātais lauks");
       } else {
          $(".shippingPostcode").text("");
       }
       if(name.length == 0 || surname.length == 0 || phone.length == 0 || adress.length == 0 || city.length == 0 || postcode.length == 0) {
         return false;
       }
     }
   },
   onApprove:function(data, actions) {
     return actions.order.capture().then(function(orderData) {
       console.log(orderData);
       const transaction = orderData.purchase_units[0].payments.captures[0];
       var name = $("#shipFirstName").val();
       var surname = $("#shipLastName").val();
       var phone = $("#shipPhone").val();
       var adress = $("#shippingAdress").val();
       var city = $("#shippingCity").val();
       var postcode = $("#shippingPostcode").val();
       var paymentId = $('#paymentId').val(transaction.id);
       $('#paypalApproved').val('true'); // pasleptajam ievades laukam iestata vertibu "true"
       $('#checkout-form').submit(); // nosuta formu
      $.ajax({
        type: "POST",
        url: "checkout.php",
        data: {
          shipFirstName: name,
          shipLastName: surname,
          shipPhone: phone,
          shippingAdress: adress,
          shippingCity: city,
          shippingPostcode: postcode,
          paymentMethod: "Maksāts ar PayPal",
          paymentId: paymentId,
          submitOrder: true
        },
        success: function(response) {
          console.log(response);
          window.location.href = "order";
        },
        error: function(xhr, status, error) {
          console.log(error);
        }
      });
      console.log('Dati:', data);
    });
   },
   onCancel:function(data) {
     window.location.replace("https://badmintonaveikals.shop/checkout");
   }
 }).render('#paypal-payment-button');
 
