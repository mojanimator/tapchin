import{D as g,i as p,o as r,e as y,a as o,b as l,g as m,t as _,n as x,c as u,w as a,f}from"./app-DeJr7fNP.js";import{_ as b}from"./InputLabel-C2gVT93H.js";import{_ as w}from"./TextInput-BUWn7uYR.js";import{L as C}from"./LoadingIcon-8dx7WYGr.js";import{S as L}from"./Selector-NdNaM2dQ.js";import{_ as k}from"./PrimaryButton-Fk5jf8Nh.js";import{_ as N}from"./_plugin-vue_export-helper-DlAUqK2U.js";import{r as V}from"./MapPinIcon-DUvwgUpM.js";import{r as $}from"./Bars2Icon-DNQAv7Yb.js";import{r as B,a as I}from"./ChevronRightIcon-9GRJ1f_S.js";const M={data(){return{loaded:!1,currentLevel:1,selected:{},selecteds:[],selectedName:null,loading:!1,cities:this.$page.props.cities,filteredCities:[]}},props:["id","label","data","modelValue"],emits:["change"],components:{PrimaryButton:k,InputLabel:b,MapPinIcon:V,Bars2Icon:$,TextInput:w,LoadingIcon:C,ChevronLeftIcon:B,ChevronRightIcon:I,Selector:L},mounted(){const t=document.getElementById("locationModal");this.modal=new g(t),this.cities=[{id:0,name:this.__("all")}];for(let e in this.$page.props.cities)this.cities.push(this.$page.props.cities[e]);this.preload()},methods:{updateLocation(t){window.axios.post(route("user.update_location"),{city_id:t}).then(e=>{e.data&&e.data.location&&(this.$page.props.user_location=e.data.location)})},preload(){let t=this.$page.props.user_location||{};this.selected.province_id=t.length>0?t[0].id:0,this.selected.county_id=t.length>1?t[1].id:0,this.selected.district_id=t.length>2?t[2].id:0,this.selected.district_id&&this.selected.county_id&&this.selected.province_id?this.selectedName=`${this.cities.filter(e=>e.id==this.selected.county_id)[0].name}-${this.cities.filter(e=>e.id==this.selected.district_id)[0].name}`:this.selected.county_id&&this.selected.province_id?this.selectedName=`${this.cities.filter(e=>e.id==this.selected.province_id)[0].name}-${this.cities.filter(e=>e.id==this.selected.county_id)[0].name}`:this.selected.province_id?this.selectedName=`${this.cities.filter(e=>e.id==this.selected.province_id)[0].name}`:this.selectedName=this.__("select_city"),this.selected.province_id?this.$emit("change",this.selected):this.modal.show(),this.loaded=!0},select(t,e){t=="province_id"?(this.selected={province_id:e,county_id:0,district_id:0},this.selectedName=`${this.cities.filter(d=>d.id==this.selected.province_id)[0].name}`,e!=0&&this.updateLocation(e)):t=="county_id"?(this.selected={province_id:this.selected.province_id,county_id:e,district_id:0},this.selectedName=`${this.cities.filter(d=>d.id==this.selected.province_id)[0].name}-${this.cities.filter(d=>d.id==this.selected.county_id)[0].name}`,e!=0&&this.updateLocation(this.selected.county_id)):t=="district_id"&&(this.selected={province_id:this.selected.province_id,county_id:this.selected.county_id,district_id:e},this.selectedName=`${this.cities.filter(d=>d.id==this.selected.county_id)[0].name}-${this.cities.filter(d=>d.id==this.selected.district_id)[0].name}`,e!=0&&this.updateLocation(this.selected.district_id)),this.$emit("change",this.selected)},selectCity(t){this.selecteds.push({id:t.id,name:t.name}),this.currentLevel++,this.getCities(this.currentLevel+1,t.id),this.filteredCities.length==0&&(this.selectedName=(this.selecteds.length>1?this.selecteds[1].name:"")+(this.selecteds.length>2?" _ "+this.selecteds[2].name:""),this.$page.props.user_location=this.selecteds,this.updateLocation(this.selecteds[this.selecteds.length-1].id),this.$emit("change",{province_id:this.selecteds[0].id,city_id:this.selecteds[this.selecteds.length-1].id}),this.modal.hide())},getCities(t,e){this.filteredCities=[];for(const d in this.cities)this.cities[d].level==t&&this.cities[d].parent_id==e&&this.filteredCities.push(this.cities[d]);return this.filteredCities}}},S={class:"flex"},P={"data-te-modal-init":"",class:"fixed left-0 top-0 backdrop-blur z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none",id:"locationModal",tabindex:"-1","aria-labelledby":"messageModalLabel","aria-hidden":"true"},z={"data-te-modal-dialog-ref":"",class:"max-w-2xl pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 px-2 sm:px-4 md:px8 min-[576px]:max-w-5xl"},E={class:"min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none max-w-xl mx-auto"},U={class:"flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4"},j={class:"text-lg text-primary-500 flex items-center font-medium leading-normal text-neutral-600",id:"locationModalLabel"},D=o("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor",class:"h-6 w-6"},[o("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M6 18L18 6M6 6l12 12"})],-1),T=[D],R={class:"relative p-4","data-te-modal-body-ref":""},q={class:"p-3"},A={class:"p-3"},F={class:"p-3"};function G(t,e,d,H,s,c){const n=p("MapPinIcon"),h=p("Selector"),v=p("PrimaryButton");return r(),y("div",S,[o("div",{class:"rounded flex items-center border border-neutral-300 hover:cursor-pointer p-2 hover:bg-gray-50 text-gray-500",onClick:e[0]||(e[0]=i=>{c.preload(),t.modal.show()})},[l(n,{class:"h-4 w-4 mx-1"}),m(" "+_(s.selectedName),1)]),o("div",P,[o("div",z,[o("div",E,[o("div",U,[o("h5",j,[l(n,{class:"h-7 w-7 mx-3"}),m(" "+_(t.__("city_select")),1)]),o("button",{class:x(["text-danger","box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"]),type:"button","data-te-modal-dismiss":"","aria-label":"Close"},T)]),o("div",R,[s.loaded?(r(),u(h,{key:0,ref:"provinceSelector",data:s.cities.filter(i=>i.level==1||i.id==0),label:`${t.__("province")} *`,onChange:e[1]||(e[1]=i=>c.select("province_id",i.target.value)),id:"province_id",modelValue:s.selected.province_id,"onUpdate:modelValue":e[2]||(e[2]=i=>s.selected.province_id=i)},{append:a(()=>[o("div",q,[l(n,{class:"h-5 w-5"})])]),_:1},8,["data","label","modelValue"])):f("",!0),s.loaded?(r(),u(h,{key:1,ref:"countySelector",data:s.cities.filter(i=>i.parent_id==s.selected.province_id||i.id==0),label:`${t.__("county")} *`,onChange:e[3]||(e[3]=i=>c.select("county_id",i.target.value)),id:"county_id",modelValue:s.selected.county_id,"onUpdate:modelValue":e[4]||(e[4]=i=>s.selected.county_id=i)},{append:a(()=>[o("div",A,[l(n,{class:"h-5 w-5"})])]),_:1},8,["data","label","modelValue"])):f("",!0),s.loaded&&s.cities.filter(i=>i.parent_id==s.selected.county_id&&i.parent_id!=0&&i.id!=0).length>0?(r(),u(h,{key:2,ref:"districtSelector",data:s.cities.filter(i=>i.level==3&&i.parent_id==s.selected.county_id||i.id==0),onChange:e[5]||(e[5]=i=>c.select("district_id",i.target.value)),label:`${t.__("district/city")} *`,id:"district_id",modelValue:s.selected.district_id,"onUpdate:modelValue":e[6]||(e[6]=i=>s.selected.district_id=i)},{append:a(()=>[o("div",F,[l(n,{class:"h-5 w-5"})])]),_:1},8,["data","label","modelValue"])):f("",!0),l(v,{onClick:e[7]||(e[7]=i=>{t.modal.hide(),t.$emit("change",s.selected)}),classes:"w-full",class:"my-2"},{default:a(()=>[m(_(t.__("accept")),1)]),_:1})])])])])])}const ie=N(M,[["render",G]]);export{ie as L};
