"""Keep readme.txt's changelog in step with changelog.txt.

changelog.txt holds every release. readme.txt holds as many of the newest as
fit under the readme parser's 5000-character section ceiling, which is a real
limit rather than a style preference: over it, wordpress.org truncates the
section and shows a changelog cut off mid-sentence.

Splitting it by hand is how it drifts, so the split is computed. changelog.txt
is the file you edit; this copies the top of it across.

    python bin/changelog.py          # rewrite the readme section
    python bin/changelog.py --check  # exit 1 if it would change
"""
import io
import os
import re
import sys

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
FULL = os.path.join(ROOT, 'changelog.txt')
README = os.path.join(ROOT, 'readme.txt')

# The parser's ceiling, less room for the pointer line and a margin. Landing
# exactly on 5000 is a warning, not a pass.
CEILING = 5000
MARGIN = 200

POINTER = 'Older releases are listed in changelog.txt, which ships with the plugin.'


def read(path):
    body = io.open(path, encoding='utf-8', newline='').read()

    return body, ('\r\n' if '\r\n' in body else '\n')


RELEASE = r'= \d+\.\d+\.\d+ ='


def releases(body):
    """Every release block in a changelog, newest first.

    Split on the version headings themselves and keep only the parts that
    begin with one. Anything ahead of the first release is dropped, because
    it is not a release: changelog.txt opens with a '== Changelog ==' heading
    and a paragraph explaining the split between the two files.

    The first version of this looked for the earliest '= ' in the file and
    split from there, then kept every part starting '= '. Both halves were
    too loose. '== Changelog ==' contains '= ' one character in, so the
    search landed inside the heading rather than at a release, and the
    paragraph beneath it then passed the startswith() test and was copied
    into readme.txt as though it were a release of its own.
    """
    parts = re.split(r'(?m)^(?=' + RELEASE + r'$)', body)

    return [p for p in parts if re.match(RELEASE, p.strip())]


def build():
    full, eol = read(FULL)
    readme, _ = read(README)

    blocks = releases(full)

    if not blocks:
        sys.exit('changelog.txt has no releases in it')

    head = '== Changelog ==' + eol + eol
    tail = eol + POINTER + eol + eol
    kept, used = [], len(head) + len(tail)

    for block in blocks:
        if used + len(block) > CEILING - MARGIN:
            break

        kept.append(block)
        used += len(block)

    if not kept:
        sys.exit('the newest release alone is over the ceiling; shorten it')

    section = head + ''.join(kept).rstrip(eol) + tail

    match = re.search(
        r'^== Changelog ==$.*?(?=^== |\Z)',
        readme,
        re.S | re.M,
    )

    if not match:
        sys.exit('readme.txt has no changelog section')

    return readme[:match.start()] + section + readme[match.end():], len(kept), used


def main():
    wanted, kept, used = build()
    current, _ = read(README)

    if '--check' in sys.argv:
        if wanted != current:
            print('readme.txt changelog is out of step. Run: python bin/changelog.py')
            sys.exit(1)

        print('readme.txt changelog is current.')
        return

    io.open(README, 'w', encoding='utf-8', newline='').write(wanted)
    print('readme.txt carries %d releases, %d characters of %d' % (kept, used, CEILING))


def self_test():
    sample = (
        '== Changelog ==\n\nProse that is not a release.\n\n'
        '= 2.0.0 =\n* new\n\n= 1.9.0 =\n* old\n'
    )
    got = releases(sample)

    assert 2 == len(got), got
    assert got[0].startswith('= 2.0.0 ='), got[0]
    assert got[1].startswith('= 1.9.0 ='), got[1]
    assert 'Prose that is not a release' not in ''.join(got)
    print('releases() drops the header and keeps both releases.')


if '--self-test' in sys.argv:
    self_test()
else:
    main()
