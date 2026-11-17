=== Dicecodes AI Blog Writer ===
Contributors: dicecodes
Tags: ai content generator, ai writer, autoblogging, seo content, blog automation
Requires at least: 6.0
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.4.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

AI blog writer that researches first, writes in your voice, and checks its own work. Bring your own API key, or drive it from Claude or ChatGPT.

== Description ==

Dicecodes AI Blog Writer writes blog posts for your WordPress site. It researches the subject first, writes in a voice you describe, scores its own draft against twenty-five checks, and rewrites what it finds wrong before anything is published.

Every feature is included. Nothing is locked or expires, and there are no credits or quotas. Your only cost is what your provider charges, and several charge nothing.

Full documentation, including a setup walkthrough and an explanation of every check it scores: https://dicecodes.com/ai-blog-writer/

**Two ways to run it**

*With your own API key.* Choose a provider — OpenAI, Anthropic, Google Gemini, Groq, Mistral, DeepSeek, OpenRouter, xAI and more — paste a key, and the site does the writing. This is the way that can write on a schedule while you are away.

*From Claude or ChatGPT.* If you already pay for one of those, connect it to your site and it does the writing on the subscription you already have. No API key, no second bill, and nothing leaves your site — the connection comes in. It speaks the Model Context Protocol (MCP), so it is not tied to one app. Your writing rules, the checks and the publishing all stay here: the app supplies the model, the plugin supplies the judgement.

You can also run a model on your own machine with Ollama, LM Studio, Jan or llama.cpp, in which case nothing is sent anywhere at all.

**How a post is written**

1. **Research.** Gathers current source material before writing a word, so the post is not assembled from memory alone.
2. **Outline.** Plans the structure, then writes the draft section by section.
3. **Critique.** Reads its own draft and lists what is vague, repetitive or padded.
4. **Revise.** Rewrites to fix what it found. If the critique finds nothing, this pass is skipped rather than run for the sake of it.
5. **Verify.** Checks that every link resolves and scores the draft. Anything below your threshold is held for review instead of published.

**It can match an article you admire**

Paste the address of any published post and the plugin reads its shape: how long it runs, how many sections, how long its sentences are, whether it uses tables and lists, how heavily it links out, how many figures it states, whether it says "I" or "you". Those measurements become your writing rules.

Structure only. None of the wording is copied, kept, or shown to a model — what it takes is public form, which belongs to nobody. There are also eight ready-made shapes to start from, from definitive guide to hands-on review.

**It measures what it was asked for**

Twenty-five checks run on the finished draft, and every one that fails is written back into the rewrite as an instruction rather than a number. Among them:

* Does the opening answer the question in its first two sentences, or clear its throat first?
* Does every section that states a figure carry a link beside it?
* Does it say anything the sources it read do not already say?
* Is the subject in the title, in a heading, and in the opening?

The loop is the point: a score you have to act on yourself is a report card, and a rewrite that never gets measured is a guess.

**It writes in your voice**

Describe your niche, your reader, your tone, your style rules, and the things you never write about. All of it is sent with every request, and a list of common AI tells is blocked by default. You can also store your own anecdotes and experience, which is the one thing AI writing structurally lacks.

**It looks after the rest of your site**

* Links each new post to your existing ones from inside the sentences, and goes back to link older posts to the new one
* Refuses a topic too similar to something you have already published
* Rewrites older posts in place when they go stale, keeping the same URL
* Adds a featured image, alt text, structured data and a contents outline
* Publishes author, reviewer, organisation and breadcrumb markup, an expertise signal search and answer engines read
* Adds a byline readers can see, with the author's own bio and profile links
* Plans around what is already ranking: the pages covering a topic are read, and the outline aimed at what they leave out
* Fills in Yoast, Rank Math or SEOPress fields when one is active, and writes the description and sharing tags itself when none is

**What it does not do**

It does not find topics for you. Every tool that does is reselling keyword-volume data, and that cannot be had for free — so charging nothing and supplying topics are not both possible. You bring the subject; the plugin does the rest.

It does not invent evidence. There is a field for your own figures, results and prices, and they are used as fact and checked against the finished draft. Nothing fills that field for you, because nothing can.

**You stay in control**

Drafts are the default. Volume limits are conservative. A monthly token cap stops runaway spend. Nothing publishes without settings you chose.

== Development ==

Development happens in the open at https://github.com/IamRamgarhia/wordpress-ai-blog-writer — the full source, the test suite, and the build scripts that produce the release zip and the translation template. Issues and pull requests are welcome there.

Nothing in the plugin is minified, compiled or generated: every file shipped is the file that was written.

== External Services ==

Dicecodes AI Blog Writer has no server of its own. It collects no analytics and sends nothing to the plugin author. It contacts only the services you configure, and only while writing a post.

**The WordPress AI Client** — on WordPress 7.0 and later, if a provider plugin is installed, the request can go through WordPress instead. No key here and no signup: the credentials live in WordPress. Offered only when genuinely available.

**AI providers** — whichever one you set up. The topic, your style settings and any gathered research are sent so the post can be written.

* OpenAI (api.openai.com) — https://openai.com/policies/terms-of-use and https://openai.com/policies/privacy-policy
* Anthropic (api.anthropic.com) — https://www.anthropic.com/legal/consumer-terms and https://www.anthropic.com/legal/privacy
* Google Gemini (generativelanguage.googleapis.com) — https://ai.google.dev/gemini-api/terms and https://policies.google.com/privacy
* xAI, for Grok (api.x.ai) — https://x.ai/legal/terms-of-service and https://x.ai/legal/privacy-policy
* Moonshot, for Kimi (api.moonshot.ai) — https://www.moonshot.ai/user-agreement and https://www.moonshot.ai/privacy-policy
* DeepSeek (api.deepseek.com) — https://cdn.deepseek.com/policies/en-US/deepseek-terms-of-use.html and https://cdn.deepseek.com/policies/en-US/deepseek-privacy-policy.html
* Groq (api.groq.com) — https://groq.com/terms-of-use/ and https://groq.com/privacy-policy/
* OpenRouter (openrouter.ai) — https://openrouter.ai/terms and https://openrouter.ai/privacy
* Mistral (api.mistral.ai) — https://mistral.ai/terms and https://mistral.ai/terms/#privacy-policy
* Together (api.together.xyz) — https://www.together.ai/terms-of-service and https://www.together.ai/privacy
* Fireworks (api.fireworks.ai) — https://fireworks.ai/terms-of-service and https://fireworks.ai/privacy-policy
* Cerebras (api.cerebras.ai) — https://www.cerebras.ai/terms-of-service and https://www.cerebras.ai/privacy-policy
* Hugging Face (router.huggingface.co) — https://huggingface.co/terms-of-service and https://huggingface.co/privacy
* Ollama, LM Studio, Jan or llama.cpp on your own machine, or a custom endpoint you define. The address is yours and the request reaches no third party, so there is no policy to link. https://ollama.com/, https://lmstudio.ai/ and https://jan.ai/

**Research providers** — optional, all off until switched on. Only the post topic is sent, and only when you write one. See Settings, Research.

* Tavily (api.tavily.com) — https://tavily.com/terms and https://tavily.com/privacy
* SerpApi (serpapi.com) — https://serpapi.com/legal#terms-of-service and https://serpapi.com/legal#privacy-policy
* Wikipedia (en.wikipedia.org) — the topic goes to its public summary API to read the opening of a matching article. No account or key. https://foundation.wikimedia.org/wiki/Policy:Terms_of_Use and https://foundation.wikimedia.org/wiki/Policy:Privacy_policy
* Hacker News search, via Algolia (hn.algolia.com) — the topic is sent as a search query. No account or key. https://www.algolia.com/policies/terms and https://www.algolia.com/policies/privacy
* A SearXNG instance you host yourself — the address is one you enter, so no third party is reached and there is no policy to link.

**Image providers** — off until you switch pictures on. There are two choices: the free service, or the same AI that writes the post. A short description of the wanted picture is sent so one can be generated; the post itself never is. Drawing with your writing AI charges its per-image rate and happens only when you choose it, so nothing is billed by accident.

* Pollinations (image.pollinations.ai) — https://pollinations.ai/terms and https://pollinations.ai/privacy — offered because it needs no account; its privacy policy covers this host by name as its keyless free API. Used whenever nothing else is set up.
* OpenAI, Google Gemini and xAI for Grok — the same three services, hosts and policies listed under AI providers above. Only the one you already write with is ever called, on the key you already gave it.

**Search engine notification** — off unless switched on under Settings, Automation.

* IndexNow (api.indexnow.org) — the address of the post, and nothing else, is sent once as it goes live, so Bing, Yandex, Seznam and Naver come and look rather than waiting. Google does not take part. https://www.bing.com/indexnow and https://www.microsoft.com/privacy/privacystatement

The plugin also fetches any URL you add to its research list, to read as source material, and with a search provider configured it opens the first few results for a topic to see how they are organised.

== Installation ==

1. Install and activate the plugin.
2. Open **AI Blog Writer** in the admin menu and choose how it should write:
   * **With your own API key** — pick a provider, paste a key. Google Gemini and Groq both have free tiers large enough to write with, and Ollama runs a model on your own machine for nothing at all.
   * **From Claude or ChatGPT** — switch on the AI client connection, copy the address it shows you, and paste that into your app. Approve the connection when your app sends you back. No API key.
3. Describe who you write for under **How it writes**, or paste the address of an article whose shape you like and let the plugin measure it.
4. Ask for a post. Drafts are the default, and anything scoring below your threshold is held for review rather than published.

== Frequently Asked Questions ==

= Where is the documentation? =

At https://dicecodes.com/ai-blog-writer/ — every help link in the plugin opens the section for the control you were looking at. It is one page rather than a copy inside the plugin and another on the web, so there is only ever one to be right.
= Do I need to pay for anything? =

The plugin is free and complete, and takes no cut of anything. The provider list is grouped by what it costs, free first, so the question is answered before you pick rather than after.

The first group runs a model on your own computer: Ollama, LM Studio, Jan and llama.cpp. No account, no key, no bill, and nothing leaves the machine. The second needs a key but not a card: Google Gemini, Groq, Mistral and Hugging Face give away usage at no cost, and OpenRouter lists a number of models that are free to call. Everything the plugin does works the same on either — there is no paid tier here to unlock.

Pictures work the same way: Pollinations needs no key and no account, and drawing with your own AI charges that provider's per-image rate and is only ever used if you ask for it. If you write with OpenAI, Gemini or Grok, the key you already pasted draws the pictures too — one key, one bill.

Free allowances move on each provider's schedule, so the settings screen links to every provider's own pricing page rather than repeating a number here that would go stale.

= Do I need an SEO plugin? =

No. If Yoast, Rank Math or SEOPress is active, this plugin writes the title and description into that plugin's own fields and leaves the rest to it. If none is active, it prints the description and the Facebook and X sharing tags for its own posts, so the description it wrote is actually used rather than sitting unread in the database.

It stops there deliberately. Filling in head tags for pages it did not write is what an SEO plugin is for, and doing it anyway would mean two plugins fighting over the same tags on any site that later installs one.

= Will posts publish without my review? =

Only if you turn that on. Drafts are the default, and any post scoring below your quality threshold is held for review even when you asked for immediate publication.

= Scheduled posts are not appearing. Why? =

WordPress only runs scheduled tasks when someone visits your site, so a quiet site may need a real system cron job. The plugin shows a notice when it detects this. You can also run `wp dicecodes run` from the command line.

= Can I use a local model? =

Yes, and it is the first group in the provider list. Ollama, LM Studio, Jan and llama.cpp each have an entry with the right address already filled in — pick one and leave the API key blank. Anything else that speaks the OpenAI protocol, vLLM included, works through the custom endpoint.

= What happens to my API keys? =

They are encrypted before being stored, shown only as a mask, and never written to logs or error messages.

== Screenshots ==

1. The overview: what is set up, what has been written, and what is waiting for you.
2. Asking for a post, with the brief it will be written to shown before anything runs.
3. How it writes — start from a shape, or paste an article you admire and have its structure measured. The panel on the right is exactly what the model is told.
4. Connecting Claude or ChatGPT over the Model Context Protocol, so the writing runs on a subscription you already pay for.
5. Everything the plugin has written, with the score each post was measured at.
6. Activity: every job, every step, and why anything stopped.

== Changelog ==

= 1.4.1 =
* Closes a hole in the 1.4.0 fix, found by the Plugins Team on 17 November 2026. Signing the link to the progress screen was the right idea, but the check only ran when the link named a job -- so a link that named none skipped it and carried on with whatever job was open, which costs the same money at the provider as naming one. What is being authorised is the act of writing, not the parameter, so a signature is now required every time: when no job is named it covers "carry on with whatever is open"
* The plugin's own link to that screen is signed like any other, so nothing changes for anybody using it

= 1.4.0 =

Fixes everything raised in the Plugins Team review of 11 November 2026, and
makes pictures a great deal simpler to set up.

Security, from the review:

* A crafted link could set an administrator's browser writing a post. The
  progress screen advances a job while it is open, and the job id came out of
  the address unverified; the AJAX nonce behind the advance could not stop it,
  because that nonce is minted by the very page the link opened. Job links are
  now signed. An unsigned one still shows the job -- old bookmarks keep working
  -- it just does not set it running.
* Every MCP write now asks WordPress whether this user may edit this post,
  rather than checking one capability that is not post-specific. A token could
  previously edit a post another user had created, published ones included.
  Publishing a draft is now a separate check again, so a contributor's token
  stops where a contributor stops.
* OAuth client registration is rate limited per caller. It has to stay
  unauthenticated -- that is what dynamic registration is -- but it did not have
  to be unlimited: registrations could be posted until the cap evicted every
  client anyone had actually connected, and none of them could authorise again.
  Eviction now drops never-used registrations first.

Fixed:

* Posts could stop with the bar frozen and no error. A stage that outran the
  host's time limit was killed after the job was marked running, and nothing
  set it back, so every poll asked for a job that could no longer be claimed --
  for ever. The screen now releases an abandoned lock itself, instead of waiting
  on an hourly cron job that a quiet site or DISABLE_WP_CRON never fires. Each
  stage also gets its own execution-time allowance.

Changed:

* Pictures are now one question with two answers: the free service, or the same
  AI that writes the post. If you write with OpenAI, Gemini or Grok, the key you
  already pasted draws the pictures -- one key, one bill, nothing else to set up.
* Removed fal.ai, Pexels and Pixabay. Between them they wanted three more
  accounts and three more keys, and the stock libraries returned photographs
  thousands of other sites had already used. Ten image settings are now two.
* Existing sites are carried over on upgrade. If you drew with OpenAI, Gemini or
  Grok and wrote with the same company, your model id moves across untouched. Any
  other picture setting becomes the free service rather than quietly drawing on
  the wrong account, and the keys that no longer have anywhere to go are deleted
  rather than left in the database.
* Corrected the readme: Pollinations does publish terms and a privacy policy,
  and that policy covers by name the host this plugin calls.
* Two MCP tools claimed credit for pictures they had not added. attach_featured()
  answered with an attachment id, and it answered with one in two different
  situations -- it had just attached a picture, or the post already had one and
  it left it alone. add_pictures added that id to a running total, so a single
  featured image that happened to be attachment 9 was reported to the connected
  client as "Added 9 pictures"; on a site with a fuller media library the number
  would have been larger still. publish_draft read the same answer as "yes" and
  reported "Added: a featured image" on every publish, whether or not it had
  added one. The function now answers about what it did, and says nothing when
  the post already had a picture.
* Four screens produced a PHP deprecation each on PHP 8.1 and later. A screen
  registered without a menu entry is not in the menu arrays, so core finds no
  title for it and then calls strip_tags() on null. The warning named a core
  file but the cause was ours. They name their own titles now.
Older releases are listed in changelog.txt, which ships with the plugin.

