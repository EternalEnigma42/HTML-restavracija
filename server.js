// Strežnik: povezuje spletno stran z bazo (SQLite)
const express = require('express');
const Baza = require('better-sqlite3');

const geslo = process.env.ADMIN_GESLO || 'spremeni-me';
const baza = new Baza('restavracija.db');   // datoteka z bazo nastane sama

// Tabela z jedmi (ustvari se, če še ne obstaja)
baza.exec(`CREATE TABLE IF NOT EXISTS jedi (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  kategorija TEXT, ime TEXT, cena REAL)`);

const app = express();
app.use(express.json());

// Preverjanje gesla (samo za admina)
function prijava(zahteva, odgovor, naprej) {
  const vneseno = Buffer.from((zahteva.headers.authorization || '').slice(6), 'base64').toString().split(':')[1];
  if (vneseno === geslo) naprej();
  else odgovor.set('WWW-Authenticate', 'Basic').sendStatus(401);
}

// Vsi lahko vidijo jedilnik
app.get('/api/jedi', (zahteva, odgovor) => {
  odgovor.json(baza.prepare('SELECT * FROM jedi ORDER BY kategorija, ime').all());
});

// Samo admin lahko dodaja, spreminja in briše
app.get('/admin', prijava, (zahteva, odgovor) => odgovor.sendFile(__dirname + '/admin.html'));

app.post('/api/jedi', prijava, (zahteva, odgovor) => {
  const { kategorija, ime, cena } = zahteva.body;
  baza.prepare('INSERT INTO jedi (kategorija, ime, cena) VALUES (?, ?, ?)').run(kategorija, ime, cena);
  odgovor.sendStatus(200);
});

app.put('/api/jedi/:id', prijava, (zahteva, odgovor) => {
  const { kategorija, ime, cena } = zahteva.body;
  baza.prepare('UPDATE jedi SET kategorija = ?, ime = ?, cena = ? WHERE id = ?').run(kategorija, ime, cena, zahteva.params.id);
  odgovor.sendStatus(200);
});

app.delete('/api/jedi/:id', prijava, (zahteva, odgovor) => {
  baza.prepare('DELETE FROM jedi WHERE id = ?').run(zahteva.params.id);
  odgovor.sendStatus(200);
});

// Spletna stran (vse datoteke v isti mapi), razen tajnih datotek
app.use((zahteva, odgovor, naprej) => {
  if (/^\/(server\.js|package|restavracija\.db|node_modules|\.git)/.test(zahteva.path)) odgovor.sendStatus(404);
  else naprej();
});
app.use(express.static(__dirname));
app.listen(3000, () => console.log('Odpri http://localhost:3000'));
