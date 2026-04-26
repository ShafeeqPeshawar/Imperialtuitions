<section class="newsletter-cta-section" id="contact"> 
    <div class="container">
        <div class="newsletter-content">
            <div class="newsletter-text">
                <h2>Join us to Grow Skills, Together!</h2>
                <p>Stay informed about new courses and special offers</p>
            </div>

            <div class="newsletter-form">

                <form id="newsletterForm">
                    @csrf

                    <input type="email" name="email" placeholder="Enter your email" required>

                    <input type="text" name="name" placeholder="Your name" required>

                    <button type="submit" class="btn-primary">
                        Get Notified
                    </button>

                    <p class="privacy-note">
                        We respect your privacy. No spam, ever.
                    </p>

                </form>

            </div>
        </div>
    </div>
</section>


<!-- Footer -->
<footer class="main-footer">
    <div class="container">
        <p>&copy; 2024 Imperial Tuitions. All rights reserved.</p>
    </div>
</footer>


<!-- RESPONSE MODAL (SUCCESS + ERROR SAME DESIGN) -->
<div id="subscribeResponseModal" class="modal-overlay">

    <div class="success-box">

        <h3 class="success-title" id="subscribeModalTitle">
            Subscription Successful
        </h3>

        <p class="success-text" id="subscribeModalMessage">
            Thank you for subscribing to 
            <strong>Imperial Tuitions</strong>.
            <br>
            Please check your email for updates and announcements.
        </p>

        <button id="subscribeModalBtn"
        class="success-btn"
        onclick="closeSubscribeModal()">
    OK
</button>

    </div>

</div>



<style>

/* ===============================
   FIX SUBSCRIBE OUTER LEFT MARGIN
=============================== */

@media (max-width:1024px){

    .subscribe-outer{

        margin-left:0;

    }

}


@media (max-width:768px){

    .subscribe-outer{

        margin-left:0;

        max-width:100%;

    }

}


@media (max-width:480px){

    .subscribe-outer{

        margin-left:0;

        padding:6px;

    }

}
.success-btn{
    padding:8px 55px;
    border:none;
    border-radius:15px;
    cursor:pointer;
    font-weight:bold;

    background:#22c55e;;
    color:#000;
}

.error-btn{
    padding:8px 55px;
    border:none;
    border-radius:15px;
    cursor:pointer;
    font-weight:bold;

    background:#ef4444 !important;
    color:#fff !important;
}  color:#fff !important;

</style>



<script>

document.getElementById('newsletterForm')
.addEventListener('submit', async function(e){

    e.preventDefault();

    const form=this;

    const formData=new FormData(form);


    try{

        const response = await fetch("{{ route('subscribe.store') }}",{

            method:"POST",

            headers:{

                'X-CSRF-TOKEN':

                document.querySelector('input[name=_token]').value

            },

            body:formData

        });


        const data = await response.json();


        /* SUCCESS CASE */

        if(response.ok && data.success){

            form.reset();

            showSubscribeModal(

                "Subscription Successful",

                "Thank you for subscribing to <strong>Imperial Tuitions</strong>.<br>Please check your email for updates and announcements."

            );

        }


        /* DUPLICATE EMAIL CASE */

        else if(response.status === 409){

           showSubscribeModal(
    "Already Subscribed",
    "This email is already subscribed to <strong>Imperial Tuitions</strong>.",
    true
);

        }


        /* VALIDATION ERROR */

        else if(response.status === 422){
showSubscribeModal(
    "Invalid Email",
    "Please enter a valid email address.",
    true
);

        }


        /* UNKNOWN ERROR */

        else{

           showSubscribeModal(
    "Something went wrong",
    "Please try again later.",
    true
);

        }

    }


    catch(error){

       showSubscribeModal(
    "Server Error",
    "Unable to process your request right now. Please try again later.",
    true
);

    }

});



/* SHOW MODAL FUNCTION */
function showSubscribeModal(title, message, isError = false){

    document.getElementById('subscribeModalTitle').innerText = title;

    document.getElementById('subscribeModalMessage').innerHTML = message;

    const modalBtn = document.getElementById('subscribeModalBtn');

    if(isError){
        modalBtn.classList.remove('success-btn');
        modalBtn.classList.add('error-btn');
    } 
    else{
        modalBtn.classList.remove('error-btn');
        modalBtn.classList.add('success-btn');
    }

    document.body.style.overflow = 'hidden';

    document.getElementById('subscribeResponseModal').style.display = 'flex';
}

/* CLOSE MODAL FUNCTION */

function closeSubscribeModal(){

    document
    .getElementById('subscribeResponseModal')
    .style.display = 'none';

    document.body.style.overflow = '';

}

</script>