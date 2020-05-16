<template>
    <div class="mt-4">
        <h4 class="font-weight-bold">Комментарии:</h4>
        <div class="new_post-item border-bottom shadow mb-2" v-for="message in messages.data">
            <div class="new_post-header d-flex justify-content-between">
                <div class="post-user">
                    <a :href="message.user.profile_url" v-if="message.user.profile_url">
                        <div class="avatar">
                            <img :src="message.user.avatar" class="img-fluid" alt=".">
                        </div>
                    </a>
                    <span class="user-status shadow" :class="message.user.is_online ? 'online' : 'offline'"></span>
                    <div v-if="message.user.rights_name"
                         class="post-of-user"
                         data-toggle="tooltip"
                         data-placement="top"
                         data-html="true"
                         :title="message.user.rights_name">
                        <svg class="icon-post">
                            <use xlink:href="/themes/default/assets/icons/sprite.svg?#check"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-grow-1 post-user d-flex flex-wrap overflow-hidden d-flex align-items-center">
                    <div class="w-100">
                        <a :href="message.user.profile_url" v-if="message.user.profile_url"><span class="user-name d-inline mr-2">{{message.user.user_name}}</span></a>
                        <div class="user-name d-inline mr-2" v-if="!message.user.profile_url">{{message.user.user_name}}</div>
                        <span class="post-meta d-inline mr-2"
                              data-toggle="tooltip"
                              data-placement="top"
                              title="Link to post">
                            {{ message.created_at }}
                        </span>
                    </div>
                    <div v-if="message.user.status" class="overflow-hidden text-nowrap text-dark-brown overflow-ellipsis small">
                        <span class="font-weight-bold">{{message.user.status}}</span>
                    </div>
                </div>
            </div>
            <div class="post-body mt-2 border-top pt-2 pb-2" v-html="message.text"></div>
            <div class="post-footer d-flex justify-content-between mt-2">
                <div class="overflow-hidden">
                    <!--<div class="post-meta d-flex">
                        <div class="user-ip mr-2">
                            <a href="<?= $item['search_ip_url'] ?>"><?= $item['ip'] ?></a>
                            <?php if (! empty($item['ip_via_proxy'])): ?>
                            / <a href="<?= $item['search_ip_via_proxy_url'] ?>"><?= $item['ip_via_proxy'] ?></a>
                            <?php endif; ?>
                        </div>
                        <div class="useragent">
                            <span><?= $item['user_agent'] ?></span>
                        </div>
                    </div>-->
                </div>
                <div class="d-flex">
                    <!--<?php if (! empty($item['reply_url'])): ?>
                    <div class="ml-3">
                        <a href="<?= $item['reply_url'] ?>"><?= __('Reply') ?></a>
                    </div>
                    <?php endif; ?>
                    <?php if (! empty($item['quote_url'])): ?>
                    <div class="ml-3">
                        <a href="<?= $item['quote_url'] ?>"><?= __('Quote') ?></a>
                    </div>
                    <?php endif; ?>
                    <?php if ($item['has_edit']): ?>
                    <div class="dropdown ml-3">
                        <div class="cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <svg class="icon text-primary">
                                <use xlink:href="<?= $this->asset('icons/sprite.svg') ?>?1#more_horizontal"/>
                            </svg>
                        </div>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="<?= $item['edit_url'] ?>" class="ml-2"><?= __('Edit') ?></a>
                            <a class="dropdown-item" href="<?= $item['delete_url'] ?>" class="ml-2"><?= __('Delete') ?></a>
                            <?php if (! empty($item['restore_url'])): ?>
                            <a class="dropdown-item" href="<?= $item['restore_url'] ?>" class="ml-2"><?= __('Restore') ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>-->
                </div>
            </div>
        </div>
        <pagination :data="messages" @pagination-change-page="getComments" class="mt-3"></pagination>
    </div>
</template>

<script>
    export default {
        name: "CommentsComponent",
        props: {
            article_id: Number,
        },
        data()
        {
            return {
                messages: {},
                loading: false,
            }
        },
        mounted()
        {
            this.getComments();
        },
        computed: {},
        methods: {
            getComments(page = 1)
            {
                this.loading = true;
                axios.get('/blog/?action=comments&article_id=' + this.article_id + '&page=' + page)
                        .then(response => {
                            this.messages = response.data;
                            this.loading = false;
                        })
                        .catch(error => {
                            alert(error);
                            this.loading = false;
                        });
            }
        }
    }
</script>
