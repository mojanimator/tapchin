import{_ as p}from"./AppLayout-BoFDhNtq.js";import c from"./DeleteUserForm-BVvReA_m.js";import l from"./LogoutOtherBrowserSessionsForm-DbNW4XN3.js";import{S as s}from"./SectionBorder-CdjCrxQo.js";import f from"./TwoFactorAuthenticationForm-CaFHkoMG.js";import u from"./UpdatePasswordForm-CCFNkdm_.js";import _ from"./UpdateProfileInformationForm-Da8mGPCe.js";import{o as e,c as d,w as n,a as i,e as r,b as t,f as a,F as h}from"./app-DeJr7fNP.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";import"./Banner-DAn6f3HS.js";import"./Tooltip-CSg56dN2.js";import"./LoadingIcon-8dx7WYGr.js";import"./XMarkIcon-CEnCPALs.js";import"./ResponsiveNavLink-D5dKpIlc.js";import"./DialogModal-B_AdtyHw.js";import"./SectionTitle-BCp5-mCw.js";import"./DangerButton-CdwuS8Ue.js";import"./InputLabel-C2gVT93H.js";import"./SecondaryButton-Bn4R_gjB.js";import"./TextInput-BUWn7uYR.js";import"./ActionMessage-Br_Oamp2.js";import"./PrimaryButton-Fk5jf8Nh.js";import"./FormSection-D6Dgpeer.js";const g=i("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"}," Profile ",-1),$={class:"max-w-7xl mx-auto py-10 sm:px-6 lg:px-8"},w={key:0},k={key:1},y={key:2},L={__name:"Show",props:{confirmsTwoFactorAuthentication:Boolean,sessions:Array},setup(m){return(o,x)=>(e(),d(p,{title:"Profile"},{header:n(()=>[g]),default:n(()=>[i("div",null,[i("div",$,[o.$page.props.jetstream.canUpdateProfileInformation?(e(),r("div",w,[t(_,{user:o.$page.props.auth.user},null,8,["user"]),t(s)])):a("",!0),o.$page.props.jetstream.canUpdatePassword?(e(),r("div",k,[t(u,{class:"mt-10 sm:mt-0"}),t(s)])):a("",!0),o.$page.props.jetstream.canManageTwoFactorAuthentication?(e(),r("div",y,[t(f,{"requires-confirmation":m.confirmsTwoFactorAuthentication,class:"mt-10 sm:mt-0"},null,8,["requires-confirmation"]),t(s)])):a("",!0),t(l,{sessions:m.sessions,class:"mt-10 sm:mt-0"},null,8,["sessions"]),o.$page.props.jetstream.hasAccountDeletionFeatures?(e(),r(h,{key:3},[t(s),t(c,{class:"mt-10 sm:mt-0"})],64)):a("",!0)])])]),_:1}))}};export{L as default};
