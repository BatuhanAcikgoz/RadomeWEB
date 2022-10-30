<form action="" method="post">
    <div class="card shadow border-left-primary">
        <div class="card-body">
            <h5><i class="icon fa fa-info-circle"></i> Kullanıcılara nasıl kredi verebilirim?</h5>
            - Admin Paneli > Kullanıcılar > Kullanıcı İsmi > Magaza > Kredi Ekle/Çıkar kısmından.</br>
            - Kullanıcıların kredi miktarlarını belirleyebilirsiniz.</br>
            - <a href="https://radome.web.tr/eklenti" target="_blank">RadomeWEB Plugin</a> ile kullanıcıların vote verdikleride, görev ya da başka şeylere bağlı olarak oyunculara kredi vermelerini sağlabilirsiniz.
        </div>
    </div>

    <br />

    <div class="form-group custom-control custom-switch">
        <input id="inputEnabled" name="enable" type="checkbox" class="custom-control-input"{if $ENABLE_VALUE eq 1} checked{/if} />
        <label class="custom-control-label" for="inputEnabled">Ödeme Yöntemini Etkinleştir</label>
    </div>

    <div class="form-group">
        <input type="hidden" name="token" value="{$TOKEN}">
        <input type="submit" value="{$SUBMIT}" class="btn btn-primary">
    </div>
</form>