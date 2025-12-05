<!-- Product Quick View Modal -->
<div class="modal fade" id="quickViewModal" tabindex="-1" aria-labelledby="quickViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.2);">
            <div class="modal-header border-0" style="padding: 24px 24px 0;">
                <h5 class="modal-title" id="quickViewModalLabel" style="font-weight: 700; color: var(--deep-charcoal);">
                    {{ __('Quick View') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 24px;" id="quickViewContent">
                <!-- Loading Spinner -->
                <div class="text-center py-5" id="quickViewLoading">
                    <div class="spinner-border text-primary" role="status" style="color: var(--burgundy-main) !important;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3" style="color: #666;">{{ __('Loading product details...') }}</p>
                </div>

                <!-- Product Content (populated via JS) -->
                <div id="quickViewProduct" style="display: none;">
                    <div class="row">
                        <!-- Product Images with Carousel -->
                        <div class="col-md-6">
                            <!-- Main Image Display -->
                            <div class="quick-view-main-image mb-3" style="position: relative; background: #f8f9fa; border-radius: 12px; overflow: hidden; aspect-ratio: 1/1;">
                                <img id="quickViewMainImage" src="" alt="" style="width: 100%; height: 100%; object-fit: contain;">
                            </div>
                            
                            <!-- Thumbnail Navigation -->
                            <div id="quickViewThumbnails" class="quick-view-thumbnails d-flex gap-2 overflow-auto" style="max-width: 100%;">
                                <!-- Thumbnails will be populated via JS -->
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="col-md-6">
                            <h3 id="quickViewName" style="font-size: 24px; font-weight: 700; color: var(--deep-charcoal); margin-bottom: 16px;"></h3>
                            
                            <div class="quick-view-rating mb-3">
                                <i class="fa-solid fa-star" style="color: #FFA500;"></i>
                                <span id="quickViewReviews" style="color: #666; font-size: 14px;"></span>
                            </div>

                            <div class="quick-view-price mb-4">
                                <span id="quickViewPrice" style="font-size: 28px; font-weight: 700; color: var(--burgundy-main);"></span>
                            </div>

                            <!-- Size Selector -->
                            <div class="mb-4" id="quickViewSizeContainer" style="display: none;">
                                <label class="form-label" style="font-weight: 600; color: var(--deep-charcoal); margin-bottom: 12px;">
                                    {{ __('Select Size') }}:
                                </label>
                                <div id="quickViewSizes" class="d-flex gap-2 flex-wrap"></div>
                            </div>

                            <!-- Color Selector -->
                            <div class="mb-4" id="quickViewColorContainer" style="display: none;">
                                <label class="form-label" style="font-weight: 600; color: var(--deep-charcoal); margin-bottom: 12px;">
                                    {{ __('Select Color') }}:
                                </label>
                                <div id="quickViewColors" class="d-flex gap-2 flex-wrap"></div>
                            </div>

                            <!-- Quantity (hidden for now, default to 1) -->
                            <input type="hidden" id="quickViewQuantity" value="1">
                            <input type="hidden" id="quickViewProductId">

                            <!-- Action Buttons -->
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-primary btn-lg" id="quickViewAddToCart" style="background: var(--burgundy-main); border: none; padding: 14px; font-weight: 600; border-radius: 8px;">
                                    <i class="fas fa-shopping-cart me-2"></i>{{ __('Add to Cart') }}
                                </button>
                                <a href="#" id="quickViewFullDetails" class="btn btn-outline-secondary btn-lg" style="border-radius: 8px; padding: 14px; font-weight: 600;">
                                    {{ __('View Full Details') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Quick View Size Buttons */
.quick-view-size-btn {
    width: 50px;
    height: 50px;
    border: 2px solid #E5E7EB;
    background: white;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.quick-view-size-btn:hover {
    border-color: var(--burgundy-main);
    transform: translateY(-2px);
}

.quick-view-size-btn.active {
    background: var(--burgundy-main);
    color: white;
    border-color: var(--burgundy-main);
}

/* Quick View Image Thumbnails */
.quick-view-thumbnails {
    scrollbar-width: thin;
    scrollbar-color: #ddd #f8f9fa;
}

.quick-view-thumbnails::-webkit-scrollbar {
    height: 6px;
}

.quick-view-thumbnails::-webkit-scrollbar-track {
    background: #f8f9fa;
    border-radius: 3px;
}

.quick-view-thumbnails::-webkit-scrollbar-thumb {
    background: #ddd;
    border-radius: 3px;
}

.quick-view-thumbnail {
    width: 70px;
    height: 70px;
    border: 2px solid #E5E7EB;
    border-radius: 8px;
    overflow: hidden;
    cursor: pointer;
    flex-shrink: 0;
    transition: border-color 0.2s ease;
}

.quick-view-thumbnail:hover {
    border-color: #999;
}

.quick-view-thumbnail.active {
    border-color: var(--burgundy-main);
    box-shadow: 0 0 0 1px var(--burgundy-main);
}

.quick-view-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    background: #f8f9fa;
}

/* Quick View Color Buttons - Override ALL inherited styles */
#quickViewModal .quick-view-color-btn {
    width: 40px !important;
    height: 40px !important;
    border: 3px solid #E5E7EB !important;
    border-radius: 50% !important;
    cursor: pointer !important;
    transition: none !important;
    position: relative !important;
    background-clip: padding-box !important;
    box-shadow: none !important;
    transform: none !important;
}

#quickViewModal .quick-view-color-btn:hover {
    border: 3px solid #E5E7EB !important;
    border-color: #E5E7EB !important;
    box-shadow: none !important;
    transform: none !important;
    outline: none !important;
}

#quickViewModal .quick-view-color-btn.active {
    border: 3px solid #000000 !important;
    border-color: #000000 !important;
    box-shadow: 0 0 0 1px #000000 !important;
    transform: none !important;
}

#quickViewModal .quick-view-color-btn.active:hover {
    border: 3px solid #000000 !important;
    border-color: #000000 !important;
    box-shadow: 0 0 0 1px #000000 !important;
    transform: none !important;
}
</style>

<script>
// Quick View Functionality
let quickViewSelectedAttributes = {};
let quickViewVariantMap = [];

function openQuickView(productId) {
    const modal = new bootstrap.Modal(document.getElementById('quickViewModal'));
    modal.show();
    
    // Reset state
    quickViewSelectedAttributes = {};
    quickViewVariantMap = [];
    document.getElementById('quickViewLoading').style.display = 'block';
    document.getElementById('quickViewProduct').style.display = 'none';
    
    // Fetch product data
    fetch(`/product/quick-view/${productId}`)
        .then(response => response.json())
        .then(data => {
            populateQuickView(data);
        })
        .catch(error => {
            console.error('Error loading product:', error);
            document.getElementById('quickViewLoading').innerHTML = '<p class="text-danger">{{ __("Error loading product") }}</p>';
        });
}

function populateQuickView(product) {
    // Hide loading, show content
    document.getElementById('quickViewLoading').style.display = 'none';
    document.getElementById('quickViewProduct').style.display = 'block';
    
    // Store variant map
    quickViewVariantMap = product.variant_map || [];
    
    // Set basic info
    document.getElementById('quickViewName').textContent = product.name;
    document.getElementById('quickViewReviews').textContent = `(${product.reviews_count} {{ __('Reviews') }})`;
    document.getElementById('quickViewProductId').value = product.id;
    document.getElementById('quickViewFullDetails').href = product.url;
    
    // Populate images
    const mainImage = document.getElementById('quickViewMainImage');
    const thumbnailsContainer = document.getElementById('quickViewThumbnails');
    
    if (product.images && product.images.length > 0) {
        // Set first image as main
        mainImage.src = product.images[0].url;
        mainImage.alt = product.images[0].alt;
        
        // Create thumbnails
        thumbnailsContainer.innerHTML = '';
        product.images.forEach((image, index) => {
            const thumbDiv = document.createElement('div');
            thumbDiv.className = 'quick-view-thumbnail' + (index === 0 ? ' active' : '');
            
            const thumbImg = document.createElement('img');
            thumbImg.src = image.url;
            thumbImg.alt = image.alt;
            
            thumbDiv.appendChild(thumbImg);
            thumbDiv.onclick = () => {
                // Update main image
                mainImage.src = image.url;
                mainImage.alt = image.alt;
                
                // Update active thumbnail
                document.querySelectorAll('.quick-view-thumbnail').forEach(t => t.classList.remove('active'));
                thumbDiv.classList.add('active');
            };
            
            thumbnailsContainer.appendChild(thumbDiv);
        });
    } else {
        // Single image fallback
        mainImage.src = product.image;
        mainImage.alt = product.name;
        thumbnailsContainer.innerHTML = '';
    }
    
    // Set price
    if (product.price_range) {
        document.getElementById('quickViewPrice').textContent = product.price_range;
    } else {
        document.getElementById('quickViewPrice').textContent = product.price;
    }
    
    // Populate sizes if available
    if (product.sizes && product.sizes.length > 0) {
        document.getElementById('quickViewSizeContainer').style.display = 'block';
        const sizesContainer = document.getElementById('quickViewSizes');
        sizesContainer.innerHTML = '';
        
        product.sizes.forEach(size => {
            const btn = document.createElement('button');
            btn.className = 'quick-view-size-btn';
            btn.textContent = size.value;
            btn.dataset.attributeId = size.attribute_id;
            btn.dataset.valueId = size.id;
            btn.onclick = () => selectSize(btn, size.attribute_id, size.id);
            sizesContainer.appendChild(btn);
        });
    }
    
    // Populate colors if available
    if (product.colors && product.colors.length > 0) {
        document.getElementById('quickViewColorContainer').style.display = 'block';
        const colorsContainer = document.getElementById('quickViewColors');
        colorsContainer.innerHTML = '';
        
        product.colors.forEach(color => {
            const btn = document.createElement('button');
            btn.className = 'quick-view-color-btn';
            btn.style.backgroundColor = color.hex_value || color.value;
            btn.dataset.attributeId = color.attribute_id;
            btn.dataset.valueId = color.id;
            btn.title = color.value;
            btn.onclick = () => selectColor(btn, color.attribute_id, color.id);
            colorsContainer.appendChild(btn);
        });
    }
}

function selectSize(btn, attributeId, valueId) {
    // Remove active from all size buttons
    document.querySelectorAll('.quick-view-size-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    quickViewSelectedAttributes[attributeId] = valueId;
    
    // Update price based on variant
    updateQuickViewPrice();
}

function selectColor(btn, attributeId, valueId) {
    // Remove active from all color buttons
    document.querySelectorAll('.quick-view-color-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    quickViewSelectedAttributes[attributeId] = valueId;
    
    // Update price based on variant
    updateQuickViewPrice();
}

function updateQuickViewPrice() {
    const attributeValueIds = Object.values(quickViewSelectedAttributes).sort();
    
    if (attributeValueIds.length === 0) {
        return; // No selection yet
    }
    
    // Find matching variant
    const matchingVariant = quickViewVariantMap.find(variant => {
        const variantAttrs = variant.attributes.slice().sort();
        return JSON.stringify(variantAttrs) === JSON.stringify(attributeValueIds);
    });
    
    // Update price display
    const priceElement = document.getElementById('quickViewPrice');
    if (matchingVariant) {
        priceElement.textContent = matchingVariant.price;
        priceElement.style.color = 'var(--burgundy-main)';
    } else {
        // No exact match - show price range or original price
        priceElement.style.color = '#999';
    }
}

// Add to Cart from Quick View
document.addEventListener('DOMContentLoaded', function() {
    const addToCartBtn = document.getElementById('quickViewAddToCart');
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function() {
            const productId = document.getElementById('quickViewProductId').value;
            const attributeValueIds = Object.values(quickViewSelectedAttributes);
            
            // Check if size and color containers are visible (product has these attributes)
            const hasSizes = document.getElementById('quickViewSizeContainer').style.display !== 'none';
            const hasColors = document.getElementById('quickViewColorContainer').style.display !== 'none';
            
            // Validate selection
            const selectedSize = document.querySelector('.quick-view-size-btn.active');
            const selectedColor = document.querySelector('.quick-view-color-btn.active');
            
            // Build validation message
            let missingSelections = [];
            if (hasSizes && !selectedSize) {
                missingSelections.push('{{ __("Size") }}');
            }
            if (hasColors && !selectedColor) {
                missingSelections.push('{{ __("Color") }}');
            }
            
            // Show error if missing selections
            if (missingSelections.length > 0) {
                const message = '{{ __("Please select") }} ' + missingSelections.join(' {{ __("and") }} ');
                
                if (typeof toastr !== 'undefined') {
                    toastr.warning(message, '{{ __("Selection Required") }}');
                } else {
                    alert(message);
                }
                return;
            }
            
            // Show loading state
            addToCartBtn.disabled = true;
            addToCartBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>{{ __("Adding...") }}';
            
            // Add to cart
            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1,
                    attribute_value_ids: attributeValueIds
                })
            })
            .then(response => response.json())
            .then(data => {
                // Reset button
                addToCartBtn.disabled = false;
                addToCartBtn.innerHTML = '<i class="fas fa-shopping-cart me-2"></i>{{ __("Add to Cart") }}';
                
                if (data.message) {
                    // Show success notification
                    if (typeof toastr !== 'undefined') {
                        toastr.success(data.message);
                    } else {
                        alert(data.message);
                    }
                    
                    // Update cart count
                    if (data.cart_count !== undefined) {
                        // Update desktop cart count
                        const cartCountEl = document.getElementById('cart-count');
                        if (cartCountEl) {
                            cartCountEl.textContent = data.cart_count;
                        }
                        
                        // Update mobile cart count
                        const cartCountMobileEl = document.getElementById('cart-count-mobile');
                        if (cartCountMobileEl) {
                            cartCountMobileEl.textContent = data.cart_count;
                        }
                    }
                    
                    // Close modal
                    bootstrap.Modal.getInstance(document.getElementById('quickViewModal')).hide();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                addToCartBtn.disabled = false;
                addToCartBtn.innerHTML = '<i class="fas fa-shopping-cart me-2"></i>{{ __("Add to Cart") }}';
                
                if (typeof toastr !== 'undefined') {
                    toastr.error('{{ __("Error adding to cart. Please try again.") }}');
                } else {
                    alert('{{ __("Error adding to cart. Please try again.") }}');
                }
            });
        });
    }
});
</script>
