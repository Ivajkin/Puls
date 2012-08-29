$j(document).ready(function () {
    new TWTR.Widget({
        version: 2,
        type: 'profile',
        rpp: 3,
        interval: 6000,
        width: 180,
        height: 300,
        theme: {
            shell: {
                background: '#353535',
                color: '#ffffff'
            },
            tweets: {
                background: '#353535',
                color: '#ffffff',
                links: '#408EDA'
            }
        },
        features: {
            scrollbar: false,
            loop: true,
            live: true,
            hashtags: true,
            timestamp: true,
            avatars: true,
            behavior: 'default'
        }
    }).render().setUser('cometokorea').start();
});