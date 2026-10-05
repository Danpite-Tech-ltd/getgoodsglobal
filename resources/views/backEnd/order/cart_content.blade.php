@php
  $product_discount = 0;
@endphp
@foreach($cartinfo as $key=>$value)
@php
  $productData = $cartProducts[$value->id] ?? null;
  $colors = $productData ? $productData->procolors : collect();
  $sizes = $productData ? $productData->prosizes : collect();

  $variantsForThisProduct = [];
  if ($productData) {
      foreach($productData->procolors as $pc) {
          $cName = $pc->color->colorName ?? $pc->color ?? 'Color #'.$pc->color_id;
          $matchingSizes = $productData->prosizes->where('color_id', $pc->color_id)->map(function($s) {
              return [
                  'size'           => $s->size,
                  'price'          => $s->SalePrice !== null ? (float)$s->SalePrice : 0,
                  'sale_price'     => (float)$s->SalePrice,
                  'regular_price'  => (float)$s->RegularPrice,
                  'purchase_price' => (float)$s->PurchasePrice,
                  'stock'          => $s->stock,
                  'color_id'       => $s->color_id,
              ];
          })->values()->toArray();

          $variantsForThisProduct[$pc->color_id] = [
              'color_id'    => $pc->color_id,
              'color_name'  => $cName,
              'color_image' => $pc->Image ?? '',
              'sizes'       => $matchingSizes,
          ];
      }

      if (empty($variantsForThisProduct) && $sizes->isNotEmpty()) {
          $sizesList = $sizes->map(function($s) {
              return [
                  'size'           => $s->size,
                  'price'          => $s->SalePrice !== null ? (float)$s->SalePrice : 0,
                  'sale_price'     => (float)$s->SalePrice,
                  'regular_price'  => (float)$s->RegularPrice,
                  'purchase_price' => (float)$s->PurchasePrice,
                  'stock'          => $s->stock,
                  'color_id'       => $s->color_id,
              ];
          })->values()->toArray();
          $variantsForThisProduct['default'] = [
              'color_id'    => null,
              'color_name'      => 'Default',
              'color_image' => '',
              'sizes'       => $sizesList,
          ];
      }
  }

  // Find currently selected color_id
  $selectedColorId = $value->options->color_id ?? null;
  if(!$selectedColorId && !empty($value->options->product_color) && $colors->isNotEmpty()) {
      $matchedColor = $colors->first(function($c) use ($value) {
          $name = $c->color->colorName ?? $c->color ?? '';
          return strtolower(trim($name)) === strtolower(trim($value->options->product_color));
      });
      if($matchedColor) {
          $selectedColorId = $matchedColor->color_id;
      }
  }

  // Filter sizes for this color
  $colorSizes = $selectedColorId ? $sizes->where('color_id', $selectedColorId) : $sizes;
@endphp
<tr id="row-{{$value->rowId}}" data-rowid="{{$value->rowId}}" data-productid="{{$value->id}}">
  <input type="hidden" class="row_variants_json" value='@json($variantsForThisProduct)' />

  <td class="text-center">
    <img height="35" width="35" class="rounded border row_img" src="{{ asset($value->options->image) ?? ''}}" style="object-fit: cover;">
  </td>
  <td>
    
    <strong class="text-dark">{{$value->name}}</strong>
  </td>
  <td>
    @if($colors->isNotEmpty())
      <select class="form-control form-select-sm cart_color" data-rowid="{{$value->rowId}}" data-productid="{{$value->id}}">
        <option value="">Select Color</option>
        @foreach($colors as $color)
          @php
            $cName = $color->color->colorName ?? $color->color ?? 'Color #'.$color->color_id;
            $isSelected = ($selectedColorId == $color->color_id) || (strtolower(trim($value->options->product_color ?? '')) === strtolower(trim($cName)));
          @endphp
          <option value="{{$color->color_id}}" 
                  data-color-name="{{$cName}}" 
                  data-color-image="{{$color->Image}}" 
                  {{ $isSelected ? 'selected' : '' }}>
            {{$cName}}
          </option>
        @endforeach
      </select>
    @elseif(!empty($value->options->product_color))
      <span class="badge badge-soft-info">{{$value->options->product_color}}</span>
    @else
      <span class="text-muted small">N/A</span>
    @endif
  </td>
  <td>
    @if($sizes->isNotEmpty())
      <select class="form-control form-select-sm cart_size" data-rowid="{{$value->rowId}}" data-productid="{{$value->id}}">
        <option value="">Select Size</option>
        @foreach($colorSizes as $sz)
          @php
            $sPrice = $sz->SalePrice !== null ? (float)$sz->SalePrice : (float)$value->price;
            $isSelected = strtolower(trim($value->options->product_size ?? '')) === strtolower(trim($sz->size));
          @endphp
          <option value="{{$sz->size}}" 
                  data-price="{{$sPrice}}" 
                  data-purchase-price="{{$sz->PurchasePrice ?? 0}}" 
                  {{ $isSelected ? 'selected' : '' }}>
            {{$sz->size}} (৳{{$sPrice}})
          </option>
        @endforeach
      </select>
    @elseif(!empty($value->options->product_size))
      <span class="badge badge-soft-warning">{{$value->options->product_size}}</span>
    @else
      <span class="text-muted small">N/A</span>
    @endif
  </td>
  <td class="text-center">
    <div class="qty-cart">
      <div class="quantity">
        <button type="button" class="minus cart_decrement" data-id="{{$value->rowId}}" value="{{$value->qty}}">-</button>
        <input type="text" value="{{$value->qty}}" readonly />
        <button type="button" class="plus cart_increment" data-id="{{$value->rowId}}" value="{{$value->qty}}">+</button>
      </div>
    </div>
  </td>
  <td class="text-center fw-bold">৳{{$value->price}}</td>
  <td class="discount text-center">
    <input type="number" class="form-control form-control-sm product_discount" value="{{$value->options->product_discount}}" placeholder="0.00" data-id="{{$value->rowId}}">
  </td>
  <td class="text-center fw-bold text-primary">৳{{($value->price - $value->options->product_discount)*$value->qty}}</td>
  <td class="text-center">
    <button type="button" class="btn btn-danger btn-xs cart_remove" data-id="{{$value->rowId}}" title="Remove"><i class="fa fa-times"></i></button>
  </td>
</tr>

@php
  $product_discount += $value->options->product_discount*$value->qty;
  Session::put('product_discount',$product_discount);
@endphp

@endforeach
