<template>


  <div class=" ">
    <div>
      <SearchInput class="w-full  " v-model="search" @input="searchMap" @search="searchMap"/>

      <LoadingIcon class="w-6 mx-auto  mx-3 fill-primary-500 " type="line-dot" v-show="loading"/>
      <ul class=" border boredr-t-0 shadow-lg rounded mb-1">
        <!--        <li v-if="searchMapResults.length==0">{{ __('no_result') }}</li>-->
        <li class="py-3 px-1 text-neutral-600 cursor-pointer hover:bg-neutral-100 rounded"
            @click="setLocation(res); getAddressFromLocation({lat: res.y, lng: res.x});"
            v-for="res in searchMapResults">
          {{ res.label }}
        </li>
      </ul>
    </div>
    <div class=" " id="map"></div>
  </div>
</template>

<script>

// import L from "leaflet";
// import L from '../neshan1.9.4'
import 'leaflet.fullscreen';
import {
  MapPinIcon,
} from "@heroicons/vue/24/outline";

import {GeoSearchControl, OpenStreetMapProvider} from 'leaflet-geosearch';
import SearchInput from "@/Components/SearchInput.vue";
import LoadingIcon from "@/Components/LoadingIcon.vue";
import {Head, Link} from '@inertiajs/vue3';

let self;
export default {
  name: "Map",
  props: ['mode', 'preload'],
  emits: ['change'],
  components: {
    SearchInput,
    MapPinIcon,
    LoadingIcon,
    Head,
  },
  data() {
    return {
      mapAddress: null,
      loading: false,
      search: null,
      map: null,
      marker: null,
      location: null,
      clearBtn: null,
      label: '',
      iranLoc: [32.4279, 53.6880],
      myIcon: null,
      mapLayers:
          {
            google: "https://mt.google.com/vt/lyrs=m&x={x}&y={y}&z={z}&s=IR&hl=fa",
            osm: "http://{s}.tile.osm.org/{z}/{x}/{y}.png",

          },
      searchLayer: null,
      mapSearchProvider: null,
      searchMapResults: [],
    }
  },
  mounted() {
    self = this;
    let fullScreenLabel = this.__('full_screen');
    let clearLabel = this.__('clear');


    // this.mapSearchProvider = new GoogleProvider();

    document.getElementById("map").style.height = "14rem";
    this.location = (this.location && this.location.split(',').length === 2 && this.location.split(',')[0] !== 'undefined') ? [parseFloat(this.location.split(',')[0]), parseFloat(this.location.split(',')[1])] : this.iranLoc;
    this.myIcon = L.icon({
      iconUrl: '/assets/images/marker-icon.png',
      shadowUrl: '/assets/images/marker-shadow.png',

    });
    // loc = iranLoc;

    this.map = new L.map('map', {
      fullscreenControl: true,
      key: import.meta.env.VITE_MAP_API,
      maptype: "neshan",
      center: [35.699756, 51.338076],
      poi: false,
      traffic: false,
      fullscreenControlOptions: {
        position: 'bottomleft',
        pseudoFullscreen: true,
        title: fullScreenLabel,

      }
    }).setView(this.location, this.location ? 16 : 8);

    // L.control.scale().addTo(map);
    L.tileLayer(this.mapLayers.osm, {
      // attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
      // maxZoom: 18,
      id: 'mapbox/streets-v11',
      // tileSize: 512,
      maxNativeZoom: 19, // OSM max available zoom is at 19.
      maxZoom: 22, // Match the map maxZoom, or leave map.options.maxZoom undefined.
      // zoomOffset: -1,
      accessToken: import.meta.env.VITE_MAP_API
    }).addTo(this.map);
    //geosearch
    this.mapSearchProvider = new OpenStreetMapProvider();
    // const searchControl = new GeoSearchControl({
    //   provider: this.mapSearchProvider,
    // });
    // this.map.addControl(searchControl);


    this.marker = L.marker(this.location, {icon: this.myIcon}).addTo(this.map);


    //add clear button
    if (this.mode !== 'view') {
      this.clearBtn = L.control({
        position: 'topleft',
        title: clearLabel,
      });

      this.clearBtn.onAdd = function (src) {

        this._div = L.DomUtil.create('div', 'leaflet-touch  leaflet-bar ');
        let el = `<a class="d-block  hoverable-dark "    title="${clearLabel}"   ><image  src="/assets/images/toggle-marker.png" class="h-full w-full p-1  " aria-hidden="true"></image></a>`;

        this._div.innerHTML = el;
        this._div.onclick = function (e) {
          e.stopPropagation();
          self.removeMarker();
        };
        return this._div;

      };
      this.clearBtn.addTo(this.map);
    }

    if (this.label)
      this.marker.bindPopup(`<small>${this.label}</small>`)/*.openPopup()*/;
    this.map.on('click', this.onMapClick);

    if (this.mode === 'edit') {
      this.marker.dragging.enable();
      this.marker.on('dragend', function (event) {
        var marker = event.target;
        var result = marker.getLatLng();
        self.setLocation({x: result.lng, y: result.lat});
        self.getAddressFromLocation({lat: result.lat, lng: result.lng});

      });
    }
    if (this.mode === 'create') {
    }

    let fullScreenEl = document.querySelector('.leaflet-control-zoom-fullscreen');
    if (fullScreenEl)
      fullScreenEl.style.backgroundImage = "url('/assets/images/vendor/leaflet.fullscreen/icon-fullscreen.svg')";

    if (this.preload) {
      this.setLocation({x: this.preload.lat, y: this.preload.lon});
    }
  },
  methods: {
    setCurrentAddress(data) {
      if (!data) {
        this.mapAddress = null;
        this.$emit('change', this.mapAddress);
        return;
      }

      let province = data.address && data.address.state ? data.address.state.replace('استان ', '') : data.address.province ? data.address.province.replace('استان ', '') : null;
      let county = data.address && data.address.county ? data.address.county.replace('شهرستان ', '') : null;
      if (province)
        province = this.$page.props.cities.filter((e) => e.parent_id == 0 && e.name == province)[0];
      province = province ? province.id : null;
      if (county && province)
        county = this.$page.props.cities.filter((e) => e.parent_id == province && e.name == county)[0];
      county = county ? county.id : null;

      this.mapAddress = {
        address: data.display_name,
        province_id: province,
        county_id: county,
        lat: data.lat,
        lon: data.lon,
      };
      this.$emit('change', this.mapAddress);

    },
    setLocation(res) {
      this.searchMapResults = [];
      if (!res) return;
      if (this.marker) {
        if (!this.marker._map) {
          this.marker.addTo(this.map);
        }

      } else {
        this.marker = L.marker(e.latlng, {icon: myIcon}).addTo(this.map);
        this.marker.dragging.enable();
      }
      this.marker.setLatLng([res.y, res.x]).update();
      // this.map.flyTo([res.y, res.x], 10);
      // this.map.panTo([res.y, res.x], 10);
      this.map.setView([res.y, res.x], this.map.getZoom(), {animation: true});


    },
    searchMap() {
      this.mapAddress = null;
      this.$emit('change', this.mapAddress);
      this.loading = true;
      this.mapSearchProvider.search({query: this.search}).then(function (result) {
        // console.log(result);
        self.searchMapResults = result;
        // if (self.searchMapResults.length == 1)
        //   self.setLocation(self.searchMapResults[0]);
      });
      this.loading = false;
    },
    searchNeshan() {
      // restarting the markers
      for (var j = 0; j < searchMarkers.length; j++) {
        if (searchMarkers[j] != null) {
          myMap.removeLayer(searchMarkers[j]);
          searchMarkers[j] = null;
        }
      }
      marker.setLatLng([centerLat.value, centerLng.value]);
      //getting term value from input tag
      var term = document.getElementById("term").value;
      //making url
      var url = `https://api.neshan.org/v1/search?term=${term}&lat=${centerLat.value}&lng=${centerLng.value}`;
      //add your api key
      var params = {
        headers: {
          'Api-Key': import.meta.env.VITE_MAP_API
        },

      };
      //sending get request
      axios.get(url, params)
          .then(data => {
            document.getElementById("resualt").innerHTML = "";
            if (data.data.count != 0) {
              document.getElementById("panel").style = "height: 60%;"
            } else {
              document.getElementById("panel").style = "height: fit-content;"
            }
            document.getElementById("resultCount").textContent = `تعداد نتایج : ${data.data.count}`
            //set center of map to marker location
            console.log(data.data);
            myMap.flyTo([centerLat.value, centerLng.value], 12);

            //for every search resualt add marker
            var i;
            for (i = 0; i < data.data.count; i++) {
              var info = data.data.items[i];
              searchMarkers[i] = L.marker([info.location.y, info.location.x], {
                icon: greenIcon,
                title: info.title
              }).addTo(myMap);
              makeDiveResualt(data.data.items[i], i);
            }


          }).catch(error => {
        document.getElementById("resualt").innerHTML = "";
        document.getElementById("panel").style = "height: fit-content;"
        document.getElementById("resultCount").textContent = `تعداد نتایج : 0`
        console.log(error.response);
      });
    },
    removeMarker() {
      this.map.eachLayer((layer) => {
        if (layer instanceof L.Marker)
          layer.remove();
      });
      this.search = null;
      this.setCurrentAddress(null);

      return this.map;
    },
    getLocation() {
      let res = null;
      this.map.eachLayer((layer) => {
        if (layer instanceof L.Marker) {
          res = layer.getLatLng().lat + "," + layer.getLatLng().lng;
          return false;
        }
      });

      return res;
    },
    onMapClick(e) {
      // this.log(e);
      this.setLocation({x: e.latlng.lng, y: e.latlng.lat});
      this.getAddressFromLocation({lat: e.latlng.lat, lng: e.latlng.lng})
      return;
      if (this.marker) {
        if (!this.marker._map) {
          this.marker.addTo(this.map);
        }

      } else {
        this.marker = L.marker(e.latlng, {icon: myIcon}).addTo(this.map);
        this.marker.dragging.enable();
      }
      this.marker.setLatLng(e.latlng);
      this.getAddressFromLocation(e.latlng);
      // L.popup()
      //     .setLatLng(e.latlng)
      //     .setContent("You clicked the map at " + e.latlng.toString())
      //     .openOn(this.map);
    },
    getAddressFromLocation(latLon) {

      if (!latLon) return null;

      if (!latLon.lat) return null;
      this.loading = true;
      window.axios.get("https://nominatim.openstreetmap.org/reverse", {
        params: {
          lat: latLon.lat,
          lon: latLon.lng,
          format: "json",
          'accept-language': 'fa'
        }
      }).then((response) => {

        if (response.status !== 200) return;
        // let addressEl = document.getElementById('address');
        if (response.data && response.data.display_name) {
          // self.log(response.data);
          self.search = response.data.display_name;
          self.setCurrentAddress(response.data);
          this.map.eachLayer((layer) => {
            if (layer instanceof L.Marker)
              layer.bindPopup(`<small>${response.data.display_name}</small>`);
            // layer.openPopup();
          });
        }
      }).finally(() => {
        // always executed
        this.loading = false;

      });
    }
  }
}
</script>

<style scoped>

</style>
