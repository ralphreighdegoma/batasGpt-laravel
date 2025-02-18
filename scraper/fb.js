const puppeteer = require('puppeteer');

async function searchFacebookPosts() {
    const browser = await puppeteer.launch({
        headless: true,
        args: ['--no-sandbox']
    });

    const page = await browser.newPage();

    // Login to Facebook
    await page.goto('https://www.facebook.com');
    // Add login logic here

    const searchTerms = [
        'got the keys UK',
        'new premises UK',
        'moving to new location UK',
        'business expansion UK',
        'new shop opening UK',
        'new office UK'
    ];

    const posts = [];

    for (const term of searchTerms) {
        // Search for posts
        await page.goto(`https://www.facebook.com/search/posts/?q=${encodeURIComponent(term)}`);
        await page.waitForSelector('[role="feed"]');

        // Scroll to load more posts
        for (let i = 0; i < 3; i++) {
            await page.evaluate(() => {
                window.scrollTo(0, document.body.scrollHeight);
            });
            await page.waitForTimeout(2000);
        }

        // Extract post data
        const termPosts = await page.evaluate(() => {
            const postElements = document.querySelectorAll('[role="article"]');
            return Array.from(postElements).map(post => {
                return {
                    text: post.innerText,
                    timestamp: post.querySelector('a[href*="/posts/"]').innerText || '',
                    link: post.querySelector('a[href*="/posts/"]').href || ''
                };
            });
        });

        posts.push(...termPosts);
    }

    await browser.close();
    return posts;
}

module.exports = {
    searchFacebookPosts
};