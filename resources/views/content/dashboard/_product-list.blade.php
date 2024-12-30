@php
    use Illuminate\Support\Str;
@endphp
{{-- content/apps/_product-list.blade.php --}}
@forelse ($products as $product)
    <div class="col-sm-6 col-lg-4">
        <div class="card p-2 h-100 shadow-none border" data-category="{{ $product->category->slug }}">
            <div class="rounded-2 text-center mb-4">
                <a href="{{ route('dashboard-detail', $product->slug) }}">
                    <img class="img-fluid product-image" src="{{ asset('assets/img/products/' . $product->image) }}"
                        alt="{{ $product->product_name }}" style="width: 332px; height: 240px; object-fit: fill;" />
                </a>
            </div>
            <div class="card-body p-4 pt-2">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    @if (isset($categoryColors[$product->category->slug]))
                        <span class="badge bg-label-{{ $categoryColors[$product->category->slug] }}">
                            {{ $product->category->name }}
                        </span>
                    @else
                        <span class="badge bg-label-primary">
                            {{ $product->category->name }}
                        </span>
                    @endif
                </div>


                <a href="{{ route('dashboard-detail', $product->slug) }}" class="h5">{{ $product->product_name }}</a>
                <p class="mt-1">{{ $product->project }}</p>
                <p class="mt-1">
                <p class="text-start mb-12 pb-md-4" style="height: 30px">
                    {{ Str::words(strip_tags($product->description), 13, '...') }}
                </p>
                </p>
                <div class="d-flex justify-content-center">
                    <a class="w-100 btn btn-primary d-flex align-items-center justify-content-center"
                        href="{{ route('dashboard-detail', $product->slug) }}">
                        <span class="me-2">Detail</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="col-12 text-center">
        <p>No products found in this category.</p>
    </div>
@endforelse
