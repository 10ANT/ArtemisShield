{{-- THE NEW data-search-text attribute includes the user's name and message for searching --}}
<div class="card request-card mb-3" 
     data-request-id="{{ $request->id }}" 
     data-lat="{{ $request->latitude }}" 
     data-lng="{{ $request->longitude }}" 
     data-search-text="{{ strtolower($request->user->name ?? '' . ' ' . $request->message) }}">

    <div class="card-body p-3">
        <div class="d-flex justify-content-between align-items-start">
            <h6 class="card-title mb-1">{{ $request->user->name ?? 'Unknown User' }}</h6>
            <span class="text-muted small" title="{{ $request->created_at->toIso8601String() }}">{{ $request->created_at->diffForHumans() }}</span>
        </div>
        <p class="card-text small mb-2">{{ $request->message }}</p>
        <div class="d-flex justify-content-between">
            <a href="tel:{{ $request->contact_number }}" class="btn btn-sm btn-outline-success @if(!$request->contact_number) disabled @endif">
                <i class="fas fa-phone me-1"></i> 
                {{ $request->contact_number ?? 'No Number' }}
            </a>
            
            @if($request->latitude && $request->longitude)
                <a href="https://bing.com/maps/default.aspx?rtp=~pos.{{ $request->latitude }}_{{ $request->longitude }}" target="_blank" class="btn btn-sm btn-outline-info">
                    <i class="fas fa-directions me-1"></i> Directions
                </a>
            @endif
        </div>
    </div>
</div>