	 <!-- Footer -->
	 <section class="footer bg-white border-top py-5">
         <div class="container">
            <div class="row">

               <div class="col-lg-4 col-md-6 order-lg-1 order-md-3 mb-sm-5">

               <h4 class="font-weight-bold"><span class="text-success">Grocery</span><span class="text-dark">Store</span></h4>
                  <h5 style="font-size: 16px;">Groceries at cheap prices</h5>
                  <p class="mb-0"><a class="text-dark" href="#"><i class="mdi mdi-cellphone-iphone"></i> +91-9089091222</a></p>
                  <p class="mb-0"><a class="text-success" href="#"><i class="mdi mdi-email"></i> support@grocerystore.in</a></p>
                  <p class="mb-0"><a class="text-primary" href="#"><i class="mdi mdi-web"></i> www.grocerystore.in</a></p>
            	</div>
                <div class="col-lg-3 col-md-6 order-lg-2 order-md-1 mb-lg-0 mb-md-5 mb-sm-5">
                  <h6 class="mb-4">We Deals in</h6>
                  <ul class="list-group">
                     <?php
                        if($categoriesResult->num_rows > 0){
                           mysqli_data_seek($categoriesResult,0);
                           while($catRow = $categoriesResult->fetch_assoc()){
                              ?>
                              <li class="list-group-item border-0 py-1 px-0"><a class="text-secondary" href="category.php?cid=<?=$catRow['id'];?>"><?=$catRow['name'];?></a></li>
                              <?php
                           }
                        }
                     ?>
                  </ul>
               </div>
               <div class="col-lg-3 col-md-6 order-lg-3 order-md-2 mb-lg-0 mb-md-5 mb-sm-5">
                  <h6 class="mb-4">Useful Link </h6>
                  <ul class="list-group">
                  <li class='list-group-item border-0 py-1 px-0'><a class="text-secondary" href="#">About Us</a></li>
                    <li class='list-group-item border-0 py-1 px-0'><a class="text-secondary" href="#">FAQ</a></li>
                  <li class='list-group-item border-0 py-1 px-0'><a class="text-secondary" href="#">Contact Us</a></li>
                  <li class='list-group-item border-0 py-1 px-0'><a class="text-secondary" href="#">Privacy Policy</a></li>
                  <li class='list-group-item border-0 py-1 px-0'><a class="text-secondary" href="#">Terms & Condition</a></li>
                  </ul>
               </div>
               
               <div class="col-lg-2 col-md-6 order-lg-4 order-md-4">
                  <h6 class="mb-4">Find Us On</h6>
                  <div class="footer-social">
                     <a style="font-size:25px;" href="#" target="_blank"><i class="fab fa-facebook"></i></a>
                     <a class="px-2" style="font-size:25px;" href="#" target="_blank"><i class="fab fa-instagram"></i></a>
                     <a style="font-size:25px;" href="#" target="_blank"><i class="fab fa-twitter-square"></i></a>
                     
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- End Footer -->
	 
	 <!-- Copyright -->
	 <section class="pt-4 pb-4 footer-bottom text-white mb-0" style="background:#1d292d!important">
         <div class="container">
            <div class="row no-gutters">
               <div class="col-lg-6 col-sm-6">
                <h6 class="text-light">Online Grocery Store</h6>
                  <p class="mt-1 mb-0"><span style="color:#b2bcc3!important">&copy; Copyright 2021</span> <strong class="text-white">GroceryStore</strong> <span style="color:#b2bcc3!important">All Rights Reserved</span><br>

              <small class="mt-0 mb-0" style="color:#b2bcc3!important">Designed  <i class="mdi mdi-heart text-danger"></i> by <a href="#" target="_blank" style="color:#d0ff15!important">Md. Akhtar</a>
                  </small>
              </p>
               </div>
               <div class="col-lg-6 col-sm-6 text-right">
                  <img alt="Payment cards" src="./images/card.png">
               </div>
            </div>
         </div>
      </section>
      <!-- End Copyright -->
	</body>
</html>