<template>
    <div>
        <button class="btn btn-light btn-sm" :class="voted > 0 ? 'liked' : ''" :disabled="voted > 0 || !can_vote">
            <svg class="icon download-button-icon mt-n1">
                <use xlink:href="/themes/default/assets/icons/sprite.svg#like"/>
            </svg>
        </button>
        <span :class="rating_color" class="ml-2 mr-2 font-weight-bold">{{ rating > 0 ? '+' : ''}}{{rating}}</span>
        <button class="btn btn-light btn-sm" :class="voted < 0 ? 'disliked' : ''" :disabled="voted < 0 || !can_vote">
            <svg class="icon download-button-icon mr-1">
                <use xlink:href="/themes/default/assets/icons/sprite.svg#dislike"/>
            </svg>
        </button>
    </div>
</template>

<script>
    export default {
        name: "ArticleLikesComponent",
        props: {
            article_id: Number,
            plus: {
                type: Number,
                default: 0
            },
            minus: {
                type: Number,
                default: 0
            },
            rating: {
                type: Number,
                default: 0
            },
            can_vote: {
                type: Boolean,
                default: false,
            },
            voted: {
                type: Number,
                default: 0,
            }
        },
        computed: {
            rating_color: function () {
                let class_name = '';
                if (this.rating > 0) {
                    class_name = 'text-success';
                } else if (this.rating < 0) {
                    class_name = 'text-danger'
                }
                return class_name;
            }
        }
    }
</script>
