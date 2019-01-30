<li class="lxhm-product">
  <div class="lxhm-product-image">
    <img src="<?=$product->lxhm_thumbnail_url[0];?>" alt="">
  </div>
  <div class="lxhm-product-name">
    <span><?=$product->name;?></span>
  </div>
  <div class="lxhm-product-spacer"></div>
  <div class="lxhm-product-price">
    <span><?=$amount;?>x <?=$product->price;?>€</span>
    <span class="lxhm-product-sum"><?=$full_amount;?>€</span>
  </div>
</li>
