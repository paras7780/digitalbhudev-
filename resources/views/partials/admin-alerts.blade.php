@if(session('status') || session('success'))
    <div style="background:#eaf5ea;border:1px solid #bce1c5;border-radius:6px;padding:12px 18px;margin-bottom:20px;color:#216a30;display:flex;align-items:center;justify-content:space-between;font-size:13px;font-weight:500">
        <span>✓ {{ session('status') ?? session('success') }}</span>
        <button type="button" onclick="this.parentElement.remove()" style="background:none;border:none;color:#216a30;font-size:18px;cursor:pointer;line-height:1">&times;</button>
    </div>
@endif

@if(session('error'))
    <div style="background:#fdeeee;border:1px solid #f8c9c9;border-radius:6px;padding:12px 18px;margin-bottom:20px;color:#c02b2b;display:flex;align-items:center;justify-content:space-between;font-size:13px;font-weight:500">
        <span>⚠ {{ session('error') }}</span>
        <button type="button" onclick="this.parentElement.remove()" style="background:none;border:none;color:#c02b2b;font-size:18px;cursor:pointer;line-height:1">&times;</button>
    </div>
@endif

@if($errors->any())
    <div style="background:#fff6e6;border:1px solid #fbdca8;border-radius:6px;padding:14px 18px;margin-bottom:20px;color:#8f5200;font-size:13px">
        <strong style="display:block;margin-bottom:6px">Please resolve the following issues:</strong>
        <ul style="margin:0;padding-left:18px;line-height:1.6">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
