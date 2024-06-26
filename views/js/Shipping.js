
$(document).ready(function () { 
    document.getElementById('saveButton').addEventListener('click', function() {
        var token = document.getElementById('token').value;
        var firstName = document.getElementById('firstName').value;
        var lastName = document.getElementById('lastName').value;
        var company = document.getElementById('company').value;
        var country = document.getElementById('country').value;
        var address1 = document.getElementById('address1').value;
        var address2 = document.getElementById('address2').value;
        var postcode = document.getElementById('postcode').value;
        var city = document.getElementById('city').value;
        var phone = document.getElementById('phone').value;
        var phoneMobile = document.getElementById('phoneMobile').value;
    
        const postButton = async () => {
            const response = await fetch('http://localhost:8000/api/shipment/save/', {
                method: "POST",
                body: JSON.stringify({
                    firstName: firstName,
                    lastName: lastName,
                    company: company,
                    country: country,
                    address1: address1,
                    address2: address2,
                    postcode: postcode,
                    city: city,
                    phone: phone,
                    phoneMobile: phoneMobile
                }),
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json'
                }
            });
    
            return response;
        };
    
        postButton().then(async (response) => {   
            if (response.status === 201) { 
                var shipmentId = await response.json();
                document.getElementById('saveButton').style.display = 'none';
    
                var printButton = document.createElement('button');
                printButton.innerText = 'Print Label';
                printButton.className = 'btn btn-primary';
                printButton.setAttribute('id', 'printButton');
                printButton.setAttribute('data-shipment-id', shipmentId);
                printButton.addEventListener('click', async function() {
                    event.preventDefault();
                    const printResponse = await fetch('http://localhost:8000/api/shipment/print/' + shipmentId, {
                        headers: {
                            'Authorization': 'Bearer ' + token
                        }
                    });
                    const base64String = await printResponse.text(); 
                    const byteCharacters = atob(base64String);
                    const byteNumbers = new Array(byteCharacters.length);
                    for (let i = 0; i < byteCharacters.length; i++) {
                        byteNumbers[i] = byteCharacters.charCodeAt(i);
                    }
                    const byteArray = new Uint8Array(byteNumbers);
                    const pdfBlob = new Blob([byteArray], { type: 'application/pdf' });
                    const url = URL.createObjectURL(pdfBlob);
                    window.open(url);
                });
                
                document.getElementById('saveButton').parentNode.appendChild(printButton); 
            }
        });
    }); 
});