<script
    src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.0.0/mdb.min.js"></script>
@vite(['resources/js/app.js'])
{{--Used by typscript files--}}
<script>
    function getPlayerUnMuteUrl(playerSteamid) {
       return "{!! env('VITE_SITE_DIR') !!}/players/"+playerSteamid+"/unmute";
    }
    function getPlayerUnBanUrl(playerSteamid) {
        return "{!! env('VITE_SITE_DIR') !!}/players/"+playerSteamid+"/unban";
    }
    function getPlayerInfoUrl(serverId) {
        return "{!! env('VITE_SITE_DIR') !!}/servers/"+serverId+"/players";
    }
    const serversListUrl = '{!! env('VITE_SITE_DIR') !!}/servers';
    const mutesListUrl = '{!! env('VITE_SITE_DIR') !!}/list/mutes';
    const recentBansUrl = '{!! env('VITE_SITE_DIR') !!}/bans';
    const recentMutesUrl = '{!! env('VITE_SITE_DIR') !!}/mutes';
    const adminListUrl = '{!! env('VITE_SITE_DIR') !!}/list/admins';
    const bansListUrl = '{!! env('VITE_SITE_DIR') !!}/list/bans';
    const playerActionUrl = '{!! env('VITE_SITE_DIR') !!}/players/action';
    const groupsListUrl = '{!! env('VITE_SITE_DIR') !!}/list/groups';
</script>



