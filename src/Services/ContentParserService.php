<?php

namespace Tuna976\NEWS\Services;

class ContentParserService
{
    /**
     * Parse content and convert URLs to embeds where applicable
     * 
     * @param string $content
     * @return string
     */
    public function parseContent(string $content): string
    {
        // Parse YouTube links
        $content = $this->parseYouTube($content);
        
        // Parse Twitter/X links
        $content = $this->parseTwitter($content);
        
        // Parse Facebook links
        $content = $this->parseFacebook($content);
        
        // Parse Instagram links
        $content = $this->parseInstagram($content);
        
        // Parse BitChute links
        $content = $this->parseBitChute($content);
        
        return $content;
    }
    
    /**
     * Parse YouTube URLs and convert to embedded iframes
     */
    protected function parseYouTube(string $content): string
    {
        $pattern = '~(?:https?://)?(?:www\.)?(?:youtube\.com/watch\?v=|youtu\.be/)([^\s&]+)~i';
        
        return preg_replace_callback($pattern, function ($matches) {
            $videoId = $matches[1];
            return '<div class="ratio ratio-16x9 my-3">
                <iframe src="https://www.youtube.com/embed/' . $videoId . '" 
                        title="YouTube video" allowfullscreen></iframe>
            </div>';
        }, $content);
    }
    
    /**
     * Parse Twitter/X URLs and convert to embedded tweets
     */
    protected function parseTwitter(string $content): string
    {
        // Match Twitter URLs
        $pattern = '~(?:https?://)?(?:www\.)?(?:twitter\.com|x\.com)/([a-zA-Z0-9_]+)/status/([0-9]+)(?:/[a-zA-Z0-9_]+)?~i';
        
        $parsedContent = preg_replace_callback($pattern, function ($matches) {
            $username = $matches[1];
            $tweetId = $matches[2];
            
            return '<div class="twitter-embed my-3 text-center">
                <blockquote class="twitter-tweet" style="margin: 0 auto;">
                    <a href="https://twitter.com/' . $username . '/status/' . $tweetId . '"></a>
                </blockquote>
                <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
            </div>';
        }, $content);
        
        return $parsedContent;
    }
    
    /**
     * Parse Facebook URLs and convert to embedded posts
     */
    protected function parseFacebook(string $content): string
    {
        // First, handle Facebook reels
        $reelPattern = '~(?:https?://)?(?:www\.)?facebook\.com/(?:reel|watch)/([0-9]+)(?:[/?][^/\s]*)?~i';
        $content = preg_replace_callback($reelPattern, function ($matches) {
            return $this->generateFacebookVideoEmbed($matches[1]);
        }, $content);
        
        // Then handle regular posts
        $postPattern = '~(?:https?://)?(?:www\.)?facebook\.com/(?:[^/]+)/(?:posts|videos)/([0-9]+)(?:[/?][^/\s]*)?~i';
        $content = preg_replace_callback($postPattern, function ($matches) {
            return $this->generateFacebookPostEmbed($matches[1]);
        }, $content);
        
        return $content;
    }
    
    /**
     * Parse Instagram URLs and convert to embedded posts
     */
    protected function parseInstagram(string $content): string
    {
        // Updated pattern to better match all possible Instagram URL formats
        $pattern = '~(?:https?://)?(?:www\.)?instagram\.com/(?:[^/]+/)?(?:p|reel|tv)/([a-zA-Z0-9_-]+)(?:/[^/]*)?/?~i';
        
        return preg_replace_callback($pattern, function ($matches) {
            $postId = $matches[1];
            $fullUrl = $matches[0];
            
            // Better detection for reels vs posts and profile links
            $isReel = stripos($fullUrl, 'reel/') !== false;
            $isProfileLink = preg_match('~/([^/]+)/(?:reel|p|tv)/~i', $fullUrl);
            
            return $this->generateInstagramEmbed($postId, $isReel, $isProfileLink);
        }, $content);
    }
    
    /**
     * Parse BitChute URLs and convert to embedded iframes
     */
    protected function parseBitChute(string $content): string
    {
        $pattern = '~(?:https?://)?(?:www\.)?bitchute\.com/video/([a-zA-Z0-9_-]+)/?~i';
        
        return preg_replace_callback($pattern, function ($matches) {
            $videoId = $matches[1];
            return $this->generateBitChuteEmbed($videoId);
        }, $content);
    }
    
    /**
     * Generate Instagram embed code with improved structure
     * 
     * @param string $postId
     * @param bool $isReel Whether the content is a reel
     * @param bool $isProfileLink Whether the URL is from a profile link
     * @return string
     */
    protected function generateInstagramEmbed($postId, $isReel = false, $isProfileLink = false): string
    {
        // Use fixed iframe approach for more consistent sizing across devices
        $height = $isReel ? "720" : "600"; // Taller for reels
        $class = $isReel ? "instagram-reel-embed" : "instagram-post-embed";
        
        return '<div class="instagram-embed my-3">
            <iframe src="https://www.instagram.com/p/' . $postId . '/embed/" 
                    class="instagram-media ' . $class . '"
                    width="100%" 
                    height="' . $height . '" 
                    frameborder="0" 
                    scrolling="no" 
                    allowtransparency="true"
                    style="max-width: 540px; width: 100%; border: none; margin: 0 auto;"></iframe>
        </div>';
    }
    
    /**
     * Parse a URL and return embed code
     * 
     * @param string|null $url
     * @return string
     */
    public function parseUrl(?string $url): string
    {
        if (!$url) {
            return '';
        }
        
        $url = trim($url);
        
        // YouTube
        if (preg_match('~(?:https?://)?(?:www\.)?(?:youtube\.com/watch\?v=|youtu\.be/)([^\s&]+)~i', $url, $matches)) {
            return $this->generateYouTubeEmbed($matches[1]);
        }
        
        // Twitter/X
        if (preg_match('~(?:https?://)?(?:www\.)?(?:twitter\.com|x\.com)/([a-zA-Z0-9_]+)/status/([0-9]+)~i', $url, $matches)) {
            return $this->generateTwitterEmbed($matches[1], $matches[2]);
        }
        
        // BitChute
        if (preg_match('~(?:https?://)?(?:www\.)?bitchute\.com/video/([a-zA-Z0-9_-]+)/?~i', $url, $matches)) {
            return $this->generateBitChuteEmbed($matches[1]);
        }
        
        // Facebook Video/Reel - Better pattern matching
        if (preg_match('~(?:https?://)?(?:www\.)?facebook\.com/(?:reel|watch)(?:/|\\?v=)([0-9]+)(?:[/?#][^/\s]*)?~i', $url, $matches)) {
            return $this->generateFacebookVideoEmbed($matches[1]);
        }
        
        // Facebook Post - Better pattern matching for various URL formats
        if (preg_match('~(?:https?://)?(?:www\.)?facebook\.com/(?:[^/]+)/(?:posts|videos)/([0-9]+)(?:[/?#][^/\s]*)?~i', $url, $matches)) {
            return $this->generateFacebookPostEmbed($matches[1]);
        }
        
        // Try general Facebook URLs for pages
        if (strpos($url, 'facebook.com') !== false) {
            return '<div class="facebook-embed my-3">
                <div id="fb-root"></div>
                <script async defer crossorigin="anonymous" 
                        src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v17.0" 
                        nonce="' . uniqid() . '"></script>
                <div class="fb-page" 
                     data-href="' . htmlspecialchars($url) . '" 
                     data-tabs="timeline" 
                     data-width="500" 
                     data-height="500" 
                     data-small-header="false" 
                     data-adapt-container-width="true" 
                     data-hide-cover="false" 
                     data-show-facepile="true">
                    <blockquote cite="' . htmlspecialchars($url) . '" class="fb-xfbml-parse-ignore">
                        <a href="' . htmlspecialchars($url) . '">Facebook Page</a>
                    </blockquote>
                </div>
            </div>';
        }
        
        // Instagram - First detect if it's a reel or a post
        if (preg_match('~instagram\.com/(?:[^/]+/)?(?:reel|p|tv)/([a-zA-Z0-9_-]+)~i', $url, $matches)) {
            $isReel = stripos($url, 'reel/') !== false;
            $isProfileLink = preg_match('~/([^/]+)/(?:reel|p|tv)/~i', $url);
            return $this->generateInstagramEmbed($matches[1], $isReel, $isProfileLink);
        }
        
        // Additional specific pattern for profile reels that might be missed
        if (preg_match('~instagram\.com/([^/]+)/reel/([a-zA-Z0-9_-]+)~i', $url, $matches)) {
            return $this->generateInstagramEmbed($matches[2]);
        }
        
        // TikTok
        if (preg_match('~(?:https?://)?(?:www\.)?(?:tiktok\.com)/@([^/]+)/video/([0-9]+)~i', $url, $matches)) {
            return $this->generateTikTokEmbed($matches[1], $matches[2]);
        }
        
        // If no known embed format is detected, return a link
        $domain = parse_url($url, PHP_URL_HOST);
        return '<div class="source-link my-3">
            <a href="' . htmlspecialchars($url) . '" target="_blank" rel="nofollow noopener" class="btn btn-outline-success">
                <i class="bi bi-box-arrow-up-right me-2"></i>
                Visit Source: ' . ($domain ?? 'External Link') . '
            </a>
        </div>';
    }

    /**
     * Generate YouTube embed code
     * 
     * @param string $videoId
     * @return string
     */
    protected function generateYouTubeEmbed($videoId): string
    {
        return '<div class="ratio ratio-16x9 my-3">
            <iframe src="https://www.youtube.com/embed/' . $videoId . '" 
                    title="YouTube video" allowfullscreen></iframe>
        </div>';
    }

    /**
     * Generate Facebook video embed code
     * 
     * @param string $videoId
     * @return string
     */
    protected function generateFacebookVideoEmbed($videoId): string
    {
        return '<div class="facebook-embed my-3">
            <div id="fb-root"></div>
            <script async defer crossorigin="anonymous" 
                    src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v17.0" 
                    nonce="' . uniqid() . '"></script>
            <div class="fb-video" 
                data-href="https://www.facebook.com/watch/?v=' . $videoId . '" 
                data-width="500"
                data-show-text="false"
                style="display: block; margin: 0 auto; max-width: 500px;">
            </div>
        </div>';
    }

    /**
     * Generate Facebook post embed code
     * 
     * @param string $postId
     * @return string
     */
    protected function generateFacebookPostEmbed($postId): string
    {
        return '<div class="facebook-embed my-3">
            <div id="fb-root"></div>
            <script async defer crossorigin="anonymous" 
                    src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v17.0" 
                    nonce="' . uniqid() . '"></script>
            <div class="fb-post" 
                 data-href="https://www.facebook.com/permalink.php?story_fbid=' . $postId . '" 
                 data-width="auto"
                 data-show-text="true"
                 style="display: block; margin: 0 auto; width: 100%; max-width: 500px;">
                <blockquote cite="https://www.facebook.com/permalink.php?story_fbid=' . $postId . '" 
                            class="fb-xfbml-parse-ignore">
                    <a href="https://www.facebook.com/permalink.php?story_fbid=' . $postId . '">Facebook Post</a>
                </blockquote>
            </div>
        </div>';
    }
    
    /**
     * Generate Twitter embed code
     * 
     * @param string $username
     * @param string $tweetId
     * @return string
     */
    protected function generateTwitterEmbed($username, $tweetId): string
    {
        return '<div class="twitter-embed my-3 text-center">
            <blockquote class="twitter-tweet" style="margin: 0 auto;">
                <a href="https://twitter.com/' . $username . '/status/' . $tweetId . '"></a>
            </blockquote>
            <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
        </div>';
    }

    /**
     * Generate TikTok embed code
     * 
     * @param string $username
     * @param string $videoId
     * @return string
     */
    protected function generateTikTokEmbed($username, $videoId): string
    {
        return '<div class="tiktok-embed my-3">
            <blockquote class="tiktok-embed" cite="https://www.tiktok.com/@' . $username . '/video/' . $videoId . '" 
                data-video-id="' . $videoId . '" style="max-width: 605px;min-width: 325px;" >
                <section></section>
            </blockquote>
            <script async src="https://www.tiktok.com/embed.js"></script>
        </div>';
    }

    /**
     * Generate BitChute embed code
     * 
     * @param string $videoId
     * @return string
     */
    protected function generateBitChuteEmbed($videoId): string
    {
        return '<div class="ratio ratio-16x9 my-3">
            <iframe src="https://www.bitchute.com/embed/' . $videoId . '/" 
                    title="BitChute video" 
                    allowfullscreen></iframe>
        </div>';
    }
}
