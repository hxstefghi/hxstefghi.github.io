<?php
include('includes/header.php');
include('includes/navbar.php');
?>

<!-- Contact Section Start -->
<section class="contact" id="contact" style="padding: 20px 20px;">
  <div class="container">
    <h1 class="text-center mb-5" style="font-size: 2.5rem; font-weight: bold; color: #333;">Get In Touch</h1>
    
    <div class="row">
      <!-- Contact Information -->
      <div class="col-md-5 mb-4 p-5">
        <h3 style="color: white; font-weight: bold;">Contact Information</h3>
        <p style="font-size: 1rem; line-height: 1.6; color: white;">Feel free to reach out to us for any inquiries or assistance. We'd love to hear from you!</p>
        
        <ul class="list-unstyled mt-4">
          <li class="mb-3">
            <i class="fa-solid fa-phone" style="color: white; font-size: 1.2rem;"></i>
            <span style="margin-left: 10px; color: white;">+9123456789</span>
          </li>
          <li class="mb-3">
            <i class="fa-solid fa-envelope" style="color: white; font-size: 1.2rem;"></i>
            <span style="margin-left: 10px; color: white;">cupoffaith@gmail.com</span>
          </li>
          <li>
            <i class="fa-solid fa-location-dot" style="color: white; font-size: 1.2rem;"></i>
            <span style="margin-left: 10px; color: white;">659 St. Joseph Avenue Brgy 186, Caloocan, Philippines</span>
          </li>
        </ul>
      </div>
      
      <!-- Contact Form -->
      <div class="col-md-7">
        <form action="#" method="post" class="p-4 rounded" style="background: #ffffff; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
          <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="name" placeholder="Full name" required>
          </div>
          
          <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" placeholder="Email address" required>
          </div>
          
          <div class="mb-3">
            <label for="number" class="form-label">Phone Number</label>
            <input type="text" class="form-control" id="number" placeholder="Phone number" required>
          </div>
          
          <div class="mb-3">
            <label for="message" class="form-label">Your Message</label>
            <textarea class="form-control" id="message" rows="5" placeholder="Type your message here..." required></textarea>
          </div>
          
          <button type="submit" class="btn w-100 text-white" style="background-color: #312922;">Submit</button>
        </form>
      </div>
    </div>
  </div>
</section>
<!-- Contact Section End -->

<?php
include('includes/footer.php');
?>
