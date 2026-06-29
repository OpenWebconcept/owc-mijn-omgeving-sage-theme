<?php

declare(strict_types=1);

namespace OWC\MijnOmgeving\Data;

use Yard\Data\PostData as YardPostData;

class PostData extends YardPostData
{
    /**
     * Whether the page title should be rendered.
     *
     * Returns false when the content already renders a title itself: an <h1> tag,
     * a 'wp:post-title' string, or the greeting block.
     */
    public function shouldShowTitle(): bool
    {
        $content = $this->content;

        return strpos($content, '<h1') === false && strpos($content, 'wp:post-title') === false && strpos($content, 'wp:theme/greeting') === false;
    }
}
