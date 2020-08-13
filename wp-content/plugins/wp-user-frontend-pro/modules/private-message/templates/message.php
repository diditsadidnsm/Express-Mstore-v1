<div id="wpuf-private-message">
<!--     <wpuf-pm-index-page></wpuf-pm-index-page>
    <wpuf-pm-single-page></wpuf-pm-single-page> -->

    <router-view></router-view>
</div><!-- wpuf-private-message -->

<!-- first page -->
<script type="text/x-template" id="tmpl-wpuf-private-message-index">
    <div id="wpuf-private-message-index">
        <!-- <router-link :to="{name: 'wpufPMSingle', params: {id: 4}}"><?php _e( 'Go', 'wpuf-pro' ); ?></router-link> -->
        <div class="mailbox">
            <form @submit.prevent="searchMessage">
                <input v-model="search" placeholder="Content search" />
                <span v-if="searching"><?php _e( 'Searching...', 'wpuf-pro' ); ?></span>
            </form>
            <button class="new-message"><?php _e( 'New Message', 'wpuf-pro' ); ?></button>
            <div v-for="message in messages">
                    <div :class="message.status">
                        <!-- <input type="checkbox" /> -->
                        <router-link :to="{name: 'wpufPMSingle', params: {id: message.user_id}}">
                            <span class="sender">{{ message.user_name }}</span>
                            <span class="title">{{ message.message }}</span>
                        </router-link>
                        <span class="date">{{ message.time }}</span>
                        <span v-on:click="deleteMessage(message.user_id)" class="delete">
                            <img :src="message.del_img" alt="delete" >
                        </span>
                </div>
            </div>
        </div>
    </div>
</script>
<!-- single chat -->
<script type="text/x-template" id="tmpl-wpuf-private-message-single">
    <div id="wpuf-private-message-single">
        <div class="chat-with">
            <router-link :to="{name: 'wpufPMIndex'}"><?php _e( '< Back', 'wpuf-pro' ); ?></router-link>
            <span><strong>{{ chat_with }}</strong></span>
        </div>
        <!-- <h3>User Id: {{ userId }}</h3> -->
        <div class="chat-container">
            <div class="chat-box">
                <div class="single-chat-container">
                    <div v-for="message in messages">
                        <div :class="message.chat_class">
                            <span v-on:click="deleteSingleMessage(message.message_id)" class="delete">
                                <img :src="message.del_img" alt="delete" >
                            </span>
                            <img :src="message.avatar" alt="Avatar" style="width:100%;">
                            <strong>{{ message.user_name }}</strong>
                            <p>{{ message.message }}</p>
                            <span class="time-right">{{ message.time }}</span> <!-- class="time-left" -->
                        </div>
                    </div>
                </div>
            </div>
            <form @submit.prevent="sendMessage">
                <textarea class="write-area" v-model="message"></textarea>
                <button type="submit"><?php _e( 'Send', 'wpuf-pro' ); ?></button>
            </form>
        </div>
    </div>
</script>
