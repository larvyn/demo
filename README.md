# Larvyn demo

Sample projects used to try [Larvyn](https://github.com/ivanmercedes/larvyn) and to take the screenshots of its [site](https://github.com/ivanmercedes/larvyn-site). Everything here is fake data.

| Folder | What it is |
| --- | --- |
| `acme-store/` | Laravel 13 store on SQLite: categories, products, customers and 340 orders, a JSON API, a checkout that queues a confirmation email, 16 tests (2 fail on purpose) and a job that always fails. |
| `billing-api/` | Placeholder project. |
| `helpdesk/` | Placeholder project. |
| `.larvyn/state.json` | Larvyn's state for the demo: the three projects above, with `acme-store` active. |
| `scripts/` | `setup.sh` prepares everything; `demo-seed.php` fills the log and the failed jobs. |

## Setup

The demo expects to live in `C:\Sites` (the paths in `.larvyn/state.json` and in the screenshots).

```sh
git clone https://github.com/ivanmercedes/larvyn-demo.git /c/Sites
/c/Sites/scripts/setup.sh
```

`setup.sh` installs Composer and npm dependencies, creates `.env` and the SQLite database, seeds it, writes a few log entries and failed jobs, runs the tests once so Larvyn has a report, and leaves one uncommitted change so the Source control view has a diff. Run it again any time to reset the data.

## Open it in Larvyn

Use the demo home so your own Larvyn projects and settings are never touched:

```sh
LARVYN_HOME='C:\Sites\.larvyn' path/to/larvyn.exe
```
