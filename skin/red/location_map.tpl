<script src="https://maps.google.com/maps?file=api&amp;v=2.x&amp;key=AIzaSyCRakKHuZid_O-BcCc1SHJdCyUMUgaCSXo" type="text/javascript"></script>

<script type="text/javascript">{literal}
    //<![CDATA[
    var map = null;
    var geocoder = null;
    function load() {
      if (GBrowserIsCompatible()) {
        map = new GMap2(document.getElementById("location_map"));
        map.setCenter(new GLatLng(37.4419, -122.1419), 13);
        geocoder = new GClientGeocoder();
      }
    }
    function showAddress(address) {
      if (geocoder) {
        geocoder.getLatLng(
          address,
          function(point) {
            if (!point) {
              alert(address + " not found");
            } else {
              map.setCenter(point, 14);
              var marker = new GMarker(point);
              map.addOverlay(marker);
              marker.openInfoWindowHtml(address);
            }
          }
        );
      }
    }
    //]]>{/literal}
    </script>

    <p class="bigger"></p>
    <p class="bigger"><div id="location_map" style="width: 500px; height: 300px"></div></p>
