<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <v-select :items="localesList" v-model="locale"></v-select>
                <vue-cal
                    :disable-views="['years','year']"
                    default-view="month"
                    :events="events"
                    :locale="locale"
                    :time-from="5 * 60" :time-to="24 * 60" :time-step="15"
                    events-on-month-view="short"
                    class="vuecal--full-height-delete"
                    :no-event-overlaps="!overlapEvents"
                >
                    <div slot="no-event">Nothing here 👌</div>
                </vue-cal>
            </div>
        </div>
    </div>
</template>


<script>
    import VueCal from 'vue-cal'
    import 'vue-cal/dist/vuecal.css'
    export default {
        components: { VueCal },
        mounted() {
            console.log('Component mounted.')
        },
        data: () => ({
          selectedEvent: {},
          showDialog: false,
          events:  default_events
        }),
    }
</script>

<style>
.today .vuecal__cell-date{
    color: red;
    font-weight: bolder;
}
.vuecal__cell-events .vuecal__event{
    margin-top: 5px;
    border-radius: 3px;
}
.vuecal__cell-events {
    padding: 0 3px;
}
.vuecal__cell-events .history{
    background-color: #3490DC;
    color: #fff;
}
.vuecal__cell-events .today{
    background-color: #794ACF;
    color: #fff;
}
.vuecal__cell-events .aol{
    background-color: #8795A1;
    color: #fff;
}
.vuecal__cell-events .schedule{
    background-color: #F66D9B;
    color: #fff;
}
body,.vuecal__title{
    background-color: #fff;
}
.vuecal__title{
    border: 1px solid #eaf0f4;
}
/* Dash indicator */
.vuecal__cell-events-count {
  width: 18px;
  height: 2px;
  color: transparent;
}

/* Cell background indicator */
.vuecal__cell--has-events {
    background-color: #fffacd;
}


.vuecal__cell{
    display: inline-block;
}
.vuecal--month-view .vuecal__no-event {display: none;}

.vuecal__event.lunch {
  background: repeating-linear-gradient(45deg, transparent, transparent 10px, #f2f2f2 10px, #f2f2f2 20px);/* IE 10+ */
  color: #999;
  display: flex;
  justify-content: center;
  align-items: center;
}
.vuecal__event.lunch .vuecal__event-time {display: none;align-items: center;}


.month-view {
    height: 100vh;
}
.month-view .vuecal__cell {
    transition: all .3s ease-in-out;
    position: relative;
    text-align: right;
    padding-bottom: 2px;
    background-color: #fff;
}
.month-view .vuecal__cell.out-of-scope {
    color: rgba(67,66,93,.3);
}
.month-view .vuecal__cell-content .vuecal__cell-date{
    display: block;
    font-size: .75rem;
    padding: 10px;
}

.vuecal--events-on-month-view .vuecal__header .vuecal__weekdays-headings{
    background-color: #f5f6fa;
    color: #a3a6b4;
    text-transform: uppercase;
    font-size: .75rem;
    font-weight: 700;
    text-align: center;
    border-bottom: 1px solid #eaf0f4;
    border-left: 1px solid #eaf0f4;
    border-right: 1px solid #eaf0f4;
}

.vuecal__weekdays-headings .vuecal__heading .full{
    display: none;
}
.vuecal__weekdays-headings .vuecal__heading .small{
    display: block;
}
.month-view .vuecal__cell:before{
    border-bottom: 1px solid #eaf0f4;
}
.vuecal{
    box-shadow: none;
}

ul.vuecal__menu {
    padding: 0;
    list-style-type: none;
    justify-content: center;
    background: none;
    border: none;
}

.vuecal__menu li{
    cursor: pointer;
    background: #fff;
    padding: 8px 18px;
    border: 1px solid #d7dae2;
    box-shadow: 0 2px 3px rgba(0,0,0,.05);
    font-size: 13px;
    transition: all .3s ease-in-out;
    height: auto;
    font-size: 13px;
}
.vuecal__menu li:not(:last-child) {
    border-right: none;
}
.vuecal__menu li.active {
    color: #3b86ff;
}
.vuecal__menu li:first-child {
    border-bottom-left-radius: 4px;
    border-top-left-radius: 4px;
}
.vuecal__menu li:last-child {
    border-bottom-right-radius: 4px;
    border-top-right-radius: 4px;
}
.vuecal__title{
    justify-content: center;
}
.vuecal__arrow--prev{
    margin-right:15px; 
}
.vuecal__arrow--next{
    margin-left:15px; 
}
.month-view .vuecal__cell.out-of-scope{
    display: none;
}
</style>
