<style>
    .share-button a img {
        height: 40px;
        padding: 10px
    }
</style>
<div class="share-button">
    <a href="javascript:void(0)">
        <img src="{{ asset('backend/images/print.webp') }}" alt="">
    </a>
    <a href="javascript:void(0)" onclick="copyToClipboard()">
        <img src="{{ asset('backend/images/copy.png') }}" alt="">
        <small class="alert-copied" style="display: none">Copied</small>
    </a>
    <a title="Bagikan ke Facebook" href="https://www.facebook.com/sharer/sharer.php?u={{ url()->full() }}"
        target="_blank" rel="noopener noreferrer">
        <img src="{{ asset('backend/images/facebook.svg') }}" alt="">
    </a>
    <a href="https://api.whatsapp.com/send?text={{ url()->full() }}" target="_blank" rel="noopener noreferrer">
        <img src="{{ asset('backend/images/wa.png') }}" alt="">
    </a>

    <a href="https://t.me/share/url?url={{ url()->full() }}" target="_blank" rel="noopener noreferrer">
        <img src="{{ asset('backend/images/telegram.webp') }}" alt="">
    </a>

</div>
<script>
    function copyToClipboard() {
        var urlToCopy = "{{ url()->full() }}";
        var input = document.createElement('input');
        input.value = urlToCopy;
        document.body.appendChild(input);
        input.select();
        document.execCommand('copy');
        document.body.removeChild(input);
        $('.alert-copied').show();
        setTimeout(() => {
            $('.alert-copied').hide();

        }, 500);

    }
</script>
