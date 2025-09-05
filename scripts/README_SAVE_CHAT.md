# Save chat utility

This small utility saves chat text piped to it into the repository under `chat_exports/`.

Usage examples (run from repo root):

1) Paste or pipe chat text into the script and save with an automatic timestamped filename:

   cat - | bash scripts/save_chat.sh

   Then paste your chat text and finish with Ctrl-D.

2) Save from a file to a custom filename:

   bash scripts/save_chat.sh my-chat.txt < /path/to/saved_chat.txt

3) Save and copy to clipboard (requires `xclip` or `xsel`):

   cat - | tee >(bash scripts/save_chat.sh) | xclip -selection clipboard

Files will be written to `chat_exports/` at the repository root.
