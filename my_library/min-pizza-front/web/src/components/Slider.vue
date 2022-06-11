<template>
	<div class="search-content">
        <div class="cat-div">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-12">
                        <div class="parteners-carousel owl-carousel owl-theme">
                            <div class="partener-item">
                                <div class="item-img"><img src="images/logo.png"></div>
                                <h5>All</h5>
                            </div>
                            <div class="partener-item">
                                <div class="item-img"><img src="images/pizza.png"></div>
                                <h5>Sea food</h5>
                            </div>
                            <div class="partener-item">
                                <div class="item-img"><img src="images/salad.png"></div>
                                <h5>salad</h5>
                            </div>
                            <div class="partener-item">
                                <div class="item-img"><img src="images/seafood.png"></div>
                                <h5>Pizza</h5>
                            </div>
                            <div class="partener-item">
                                <div class="item-img"><img src="images/vegan.png"></div>
                                <h5>vegan</h5>
                            </div>
                            <div class="partener-item">
                                <div class="item-img"><img src="images/pizza.png"></div>
                                <h5>Sea food</h5>
                            </div>
                            <div class="partener-item">
                                <div class="item-img"><img src="images/salad.png"></div>
                                <h5>salad</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'Slider',
  props: {
    msg: String,
  },
});
</script>