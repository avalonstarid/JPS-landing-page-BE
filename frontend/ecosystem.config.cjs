module.exports = {
  apps: [
    {
      name: '80-Skeleton',
      port: 80,
      exec_mode: 'cluster',
      instances: 2, // or 'max'
      script: './.output/server/index.mjs',
      interpreter: '/usr/local/bin/bun', // Path to the Bun interpreter or /home/skeleton/.bun/bin/bun
    },
  ],
}
