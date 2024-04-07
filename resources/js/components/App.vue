<template>
    <div v-if="this.article !== null" class="alert  alert-primary" role="alert">
        Добавлен новый комментарий к статье <a :href="`/article/${ this.article.id }`"><strong>{{ this.article.name }}</strong></a> 
    </div>
</template>
    
<script>
    export default {
    data() { return { article:null } },
        created() {
            window.Echo.channel('new-comment-channel').listen('NewCommentEvent', (article) => {
                console.log(article.article.name, article.article.id);
                this.article=article.article;
            })
        }
    }
</script>